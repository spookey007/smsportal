<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Group;
use App\Models\Contact;
use App\Models\GroupContact;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    protected $pageTitle;

    public function emailGroup()
    {
        $this->pageTitle = 'Manage Email Groups';
        return $this->groupData('email', 1);
    }

    public function smsGroup()
    {
        $this->pageTitle = 'Manage SMS Groups';
        return $this->groupData('sms', 2);
    }

    public function banned()
    {
        $this->pageTitle    = 'Manage Banned Group';
        return $this->groupData('banned', 0);
    }

    public function groupData($scope, $groupType)
    {
        $pageTitle = $this->pageTitle;

        $groups =  Group::$scope()->withCount('contact')->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('admin.group.list', compact('pageTitle', 'groups', 'groupType'));
    }

    public function saveGroup(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required|in:1,2,0',   //1 = Email Group,2 = SMS Group,0 = banned
        ]);

        $sameGroup = Group::where('id', '!=', $id)->where('name', $request->name)->where('type', $request->type)->first();

        if ($sameGroup) {
            $notify[] = ['error', 'Group already exists'];
            return back()->withNotify($notify)->withInput();
        }

        if ($id) {
            $group         = Group::findOrFail($id);
            $group->status = $request->status ? 1 : 0;
            $message       = "Group updated successfully";
        } else {
            $group         = new Group();
            $group->type   = $request->type;
            $message       = "Group added successfully";
        }

        $group->name       = $request->name;
        $group->save();
        $notify[]          = ['success', $message];
        return back()->withNotify($notify);
    }

    public function viewGroupContact($id, $groupType)
    {
        $group        = Group::where('id', $id)->where('type', $groupType)->firstOrFail();
        $pageTitle    = 'View Group: ' . $group->name;
        $contacts     = GroupContact::where('group_id', $id)->orderBy('id', 'DESC')->with('contact')->paginate(getPaginate());
        return view('admin.group.view_contact', compact('pageTitle', 'group', 'contacts', 'groupType'));
    }

    public function contactSaveToGroup(Request $request, $groupId, $groupType)
    {
        $request->validate([
            'contacts'   => 'required|array',
            'contacts.*' => 'required|integer|exists:contacts,id',
        ]);

        $group = Group::where('id', $groupId)->where('type', $groupType)->firstOrFail();
        foreach ($request->contacts as $contact) {
            $groupContact = GroupContact::where('group_id', $request->group_id)->where('contact_id', $contact)->exists();
            if (!$groupContact) {
                $group->contact()->attach($contact);
            }
        }

        $notify[] = ['success', "Contact added successfully"];
        return back()->withNotify($notify);
    }

    public function deleteContactFromGroup($id)
    {
        GroupContact::where('id', $id)->delete();
        $notify[]     = ['success', "Contact successfully removed"];
        return back()->withNotify($notify);
    }


    public function importContactToGroup(Request $request, $groupId, $groupType)
    {

        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', new FileTypeValidate(['csv', 'xlsx', 'txt'])]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        $group = Group::where('id', $groupId)->where('type', $groupType)->first();

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => "Group not found"
            ]);
        }

        if (!$group->status) {
            return response()->json([
                'success' => false,
                'message' => "Currently Group Inactive"
            ]);
        }

        $columnNames       = ['email'];
        $uniqueColumns = ['email'];

        if ($groupType == 2) {
            $columnNames       = ['mobile'];
            $uniqueColumns = ['mobile'];
        }

        $contactId = [];

        $collection = [];

        try {
            $fileReadData = importFileReader($request->file, $columnNames, $uniqueColumns, true);
            if (count($fileReadData->allData)) {
                foreach ($fileReadData->allData as $item) {
                    $collection[] = @$item[0];
                }
            }
            $columnName = implode(',', $columnNames);
            $contactId  = Contact::where('status', 1)->whereIn($columnName, $collection)->whereDoesntHave('groupContact')->select('id') ->pluck('id')->toArray();
            if (count($contactId) > 0) { }

            count($contactId) > 0 ? $group->contact()->attach($contactId) : '';

            $notify['success'] = true;
            $notify['message'] = count($contactId) . " contacts added to group";
        } catch (Exception $ex) {
            $notify['success'] = false;
            $notify['message'] = $ex->getMessage();
        }
        return response()->json($notify);
    }
}
