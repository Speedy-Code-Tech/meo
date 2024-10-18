<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeStatusBuildingPermitFormRequest;
use App\Models\ApplicationDocument;
use App\Models\ApplicationForm;
use App\Models\BuildingPermitForm;
use App\Models\BusinessPermitForm;
use App\Models\Client;
use App\Models\Notification;
use App\Models\Requirement;
use App\Models\User;
use App\Notifications\NewNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ApprovalController extends Controller
{
    public function index()
    {
        $queue = ApplicationForm::paginate(5);
        $queue = ApplicationForm::with(['client', 'checkedBy'])->paginate(5);
        return Inertia::render('Admin/Approval/Index', [
            'queue' => $queue
        ]);
    }

    public function changeStatus(Request $request)
    {
        $validated = $request->all();
        $status = $validated['status'];
        $types = $validated['type'];
        $remarks = $validated['remarks'];

        $record = ApplicationForm::find($validated['id']);
        $user = Client::find($record->client_id)->user;
        $remarks = $status == "Approved" ? "Completed" : $remarks;
        // dd($record->client_id);
        switch ($types) {
            case "1":
                $type = "Business Permit (New)";
                break;
            case "2":
                $type = "Building Permit";
                break;
            default:
                $type = "Business Permit (Renewal)";
                break;
        }
        $msg = '';
        $notif = '';
        switch ($status) {
            case 'Approved':
                $msg = "Admin approved your application in $type.";
                $notif = "Admin approved your application in $type.";
                break;
            case 'Returned':
                $msg = "Admin returned your application in $type.";
                $notif = "Admin returned your application in $type.";
                break;
            default:
                $msg = "Admin rejected your application form in $type. Remarks: " . $remarks;
                $notif = "Admin rejected your application form in $type.";
                break;
        };

        $notification = Notification::create([
            'description' => $msg,
            'client_id' => $record->client_id,
            'application_form_id' => $record->id,
        ]);
        $user->notify(new NewNotification($notif, $notification));
        $record->update([
            "status" => $status,
            "remarks" => $remarks,
            "checked_by" => auth()->user()->client_id,
        ]);
        return Redirect::route('admin.approval.index');
    }

    public function addDocumentRemarks(Request $request)
    {
        $document = ApplicationDocument::find($request->id);
        $document->update(['remarks' => $request->remarks]);
        return Inertia::render('Admin/Approval/Partials/ApplicationFormView');
    }

    public function getRecord(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        $clientId = $request->clientId;

        $form = ApplicationForm::find($id);
        $record = Requirement::getRequirementsWithApplicationForm($id, $clientId, 5, $type);

        $client = Client::find($clientId);
        // $client = User::where('client_id', $clientId)
        //     ->first();

        $age = $this->calculateAge($client['birthday']);
        $client['age'] = $age;

        return Inertia::render('Admin/Approval/Partials/ApplicationFormView', [
            'form' => $form,
            'record' => $record,
            'client' => $client,
            'typeProp' => $type,
            'params' => $request->only(['id', 'type', 'clientId'])
        ]);
    }

    /**
     * Calculate age based on birtdate
     * 
     * @param string|null $date. Date of Birth
     * @return int|null
     */
    private function calculateAge(?string $date): ?int
    {
        if (is_null($date)) {
            return null;
        }

        $birthdate = Carbon::parse($date);
        $today = Carbon::now();
        $age = $today->diffInYears($birthdate);

        return $age;
    }
}
