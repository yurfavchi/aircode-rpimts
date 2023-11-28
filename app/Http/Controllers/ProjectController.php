<?php

namespace App\Http\Controllers;
use App\Notifications\ResearchProposalSubmissionNotification;
use Exception;
// use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\CallForProposal;
use App\Models\LineItemBudget;
use Illuminate\Http\Request;
use App\Models\Revision;
use App\Models\Project;
use App\Models\User;
use App\Models\Review;
use App\Models\Member;
use App\Models\Task;
use App\Models\File;
use App\Models\Version;
// use Rorecek\Ulid\Ulid;

class ProjectController extends Controller
{

    public function index()
    {
        try {
            // Fetch records and related data from the database
            $projects = Project::all();
            $reviewers = User::whereIn('id', Review::pluck('user_id'))->get();
            $call_for_proposals = CallForProposal::all();

            return view('projects.index', compact('projects', 'reviewers', 'call_for_proposals'));
        } catch (Exception $e) {
            // Handle the exception, you can log it for debugging or display an error message to the user.
            return redirect()->back()->with('error', 'An error occurred while fetching project data: ' . $e->getMessage());
        }
    }


    public function create()
    {
        try {
            $project = new Project();
            $users = User::all();
            $call_for_proposals = CallForProposal::all();

            return view('projects.create', ['call_for_proposals' => $call_for_proposals]);
        } catch (Exception $e) {
            // Handle the exception, you can log it for debugging or display an error message to the user.
            return redirect()->route('projects.create')->with('error', 'An error occurred while creating the project: ' . $e->getMessage());
        }
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'call_for_proposal_id' => 'required',
                'project_name' => 'required',
                'research_group' => 'required',
                'introduction' => 'required',
                'aims_and_objectives' => 'required',
                'background' => 'required',
                'expected_research_contribution' => 'required',
                'proposed_methodology' => 'required',
                'workplan' => 'required',
                'resources' => 'required',
                'references' => 'required',
            ]);

            $userId = Auth::id();

            // Define the character pool for random letters (capital letters only)
            $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

            // Generate a string of 4 random letters
            $randomLetters = '';
            for ($i = 0; $i < 4; $i++) {
                $randomLetters .= $letters[random_int(0, strlen($letters) - 1)];
            }

            // Generate a string of 4 random numbers
            $randomNumbers = sprintf('%04d', random_int(0, 9999));

            // Combine and jumble the random letters and numbers
            $ulidValue = str_shuffle($randomLetters . $randomNumbers);

            // Create the Project model with the ULID
            $projects = new Project(['tracking_code' => $ulidValue]);

            // $projects = new Project;
            $projects->project_name = $request->project_name;
            if ($request->has('draft_submit')) {
                // Set the project status as 'draft'
                $projects->status = 'draft';
            } else {
                // Set the project status as 'under evaluation' for the regular submission
                $projects->status = 'under evaluation';

            }
            $projects->user_id = $userId;
            $projects->call_for_proposal_id = $request->call_for_proposal_id;
            $projects->project_name = $request->project_name;
            $projects->research_group = $request->research_group;
            $projects->introduction = $request->introduction;
            $projects->aims_and_objectives = $request->aims_and_objectives;
            $projects->background = $request->background;
            $projects->expected_research_contribution = $request->expected_research_contribution;
            $projects->proposed_methodology = $request->proposed_methodology;
            $projects->workplan = $request->workplan;
            $projects->resources = $request->resources;
            $projects->references = $request->references;

            $projects->save();

            // Attach selected "Call for Proposals" to the project

            $researcher = User::find($userId);
            $researcherMail = $researcher->email;
            $projectTitle = $projects->project_name;
            $trackingCode = $projects->tracking_code;
            $createdAt = $projects->created_at;
            $directors = User::where('role', true)->get();

            if ($directors) {
                foreach ($directors as $director) {
                    $director->notify(new ResearchProposalSubmissionNotification($projects->id, $trackingCode, $createdAt, $userId, $researcherMail, $projectTitle, $projects));
                }
            }
            if ($researcher) {

                $researcher->notify(new ResearchProposalSubmissionNotification($projects->id, $trackingCode, $createdAt, $userId, $researcherMail, $projectTitle, $projects));
            }

            return redirect()->route('projects')->with('success', 'Research Proposal Successfully Submmitted!');
        } catch (Exception $e) {
        // Handle the exception here, you can log it or return an error response
        return redirect()->route('projects')->with('error', 'An error occurred while submitting the research proposal: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $tasks = Task::where('project_id', $id)->get();
            $teamMembers = Member::where('project_id', $id)->get();
            $allLineItems = LineItemBudget::all();
            $lineItems = LineItemBudget::where('project_id', $id)->get();
            $files = File::where('project_id', $id)->get();
            $reviewers = Review::where('user_id', $id)->get();
            $toreview = Review::where('project_id', $id)->get()->first();
            $members = User::where('role', 3)->get();
            $reviewersss = User::where('role', 4)->get();
            $data = Project::findOrFail($id);
            $records = Project::with('versions')->findOrFail($id);

            // $reviewerCommented = Review::where('user_id', Auth::user()->id)->get();
            // $comments = Review::findOrFail($id);

            $reviewerCommented = Review::where('user_id', Auth::user()->id)
            ->where('project_id', $id)
            ->count();

            // Calculate the total of all line items
            $totalAllLineItems = 0;
            foreach ($lineItems as $item) {
                $totalAllLineItems += $item->amount; // Adjust this based on your LineItemBudget structure
            }

            return view('submission-details.show', compact('id', 'records', 'reviewers', 'toreview','reviewersss', 'teamMembers',
                        'lineItems', 'allLineItems', 'files', 'totalAllLineItems', 'members', 'tasks', 'data',
                        'reviewerCommented'));
        } catch (Exception $e) {
            // Handle the exception, you can log it for debugging or display an error message to the user.
            return redirect()->back()->with('error', 'An error occurred while showing the project: ' . $e->getMessage());
        }

    }

    public function edit($id)
    {
        try {
            $reviewers = User::where('role', 4)->get();
            $projects = Project::findOrFail($id);
            $projectTeam = Member::findOrFail($id);

            $records = Project::findOrFail($id);

            return view('projects.edit', compact('projects', 'reviewers', 'projectTeam', 'records'));
        } catch (Exception $e) {
            // Handle the exception, you can log it for debugging or display an error message to the user.
            return redirect()->back()->with('error', 'An error occurred while editing the project: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $record = Project::findOrFail($id);
            $newVersionNumber = $record->versions()->max('version_number') + 1;

            $newVersion = new Version([
                'version_number' => $newVersionNumber,
                'tracking_code' => $request->input('tracking_code'),
                'project_name' => $request->input('project_name'),
                'research_group' => $request->input('research_group'),
                'introduction' => $request->input('introduction'),
                'aims_and_objectives' => $request->input('aims_and_objectives'),
                'background' => $request->input('background'),
                'expected_research_contribution' => $request->input('expected_research_contribution'),
                'proposed_methodology' => $request->input('proposed_methodology'),
                'workplan' => $request->input('workplan'),
                'resources' => $request->input('resources'),
                'references' => $request->input('references'),
                // 'total_budget' => $request->input('total_budget'),
            ]);

            $record->versions()->save($newVersion);

            // Update the project record with the new values from the form
            $record->project_name = $request->input('project_name');
            $record->research_group = $request->input('research_group');
            $record->introduction = $request->input('introduction');
            $record->aims_and_objectives = $request->input('aims_and_objectives');
            $record->background = $request->input('background');
            $record->expected_research_contribution = $request->input('expected_research_contribution');
            $record->proposed_methodology = $request->input('proposed_methodology');
            $record->workplan = $request->input('workplan');
            $record->resources = $request->input('resources');
            $record->references = $request->input('references');

            // Save the updated project record
            $record->save();

            return redirect()->back()->with('success', 'Reseach Proposal Successfully Deleted!');
        } catch (Exception $e) {
            // Handle the exception, you can log it or return an error response
            return redirect()->back()->with('error', 'An error occurred while updating the file: ' . $e->getMessage());
        }
    }

    // public function update(Request $request, $id)
    // {
    //     try {
    //         // Find the original project record
    //         $originalProject = Project::findOrFail($id);

    //         // Get the latest revision for the original project
    //         $latestRevision = Revision::where('original_proposal_id', $originalProject->id)
    //             ->orderBy('version', 'desc')
    //             ->first();

    //         // If there is a latest revision, increment the version
    //         $version = $latestRevision ? $latestRevision->version + 1 : 1;

    //         // Save the new revision
    //         $revision = new Revision();
    //         $revision->original_proposal_id = $originalProject->id;
    //         $revision->version = $version;
    //         $revision->changes = 'Updated project details'; // You may want to customize this based on the actual changes
    //         // Set other fields...
    //         $revision->save();

    //         // Update the original project record with the new values from the form
    //         // $originalProject->update([
    //         //     'project_name' => $request->input('project_name'),
    //         //     'research_group' => $request->input('research_group'),
    //         //     'introduction' => $request->input('introduction'),
    //         //     'aims_and_objectives' => $request->input('aims_and_objectives'),
    //         //     'background' => $request->input('background'),
    //         //     'expected_research_contribution' => $request->input('expected_research_contribution'),
    //         //     'proposed_methodology' => $request->input('proposed_methodology'),
    //         //     'workplan' => $request->input('workplan'),
    //         //     'resources' => $request->input('resources'),
    //         //     'references' => $request->input('references'),
    //         // ]);
    //          // Update the project record with the new values from the form
    //                 $originalProject->project_name = $request->input('project_name');
    //                 $originalProject->research_group = $request->input('research_group');
    //                 $originalProject->introduction = $request->input('introduction');
    //                 $originalProject->aims_and_objectives = $request->input('aims_and_objectives');
    //                 $originalProject->background = $request->input('background');
    //                 $originalProject->expected_research_contribution = $request->input('expected_research_contribution');
    //                 $originalProject->proposed_methodology = $request->input('proposed_methodology');
    //                 $originalProject->workplan = $request->input('workplan');
    //                 $originalProject->resources = $request->input('resources');
    //                 $originalProject->references = $request->input('references');

    //                 // Save the updated project record
    //                 $originalProject->save();

    //         return redirect()->back()->with('success', 'Research Proposal Successfully Updated!');
    //     } catch (Exception $e) {
    //         // Handle the exception, you can log it or return an error response
    //         return redirect()->back()->with('error', 'An error occurred while updating the project: ' . $e->getMessage());
    //     }
    // }

    public function destroy($id)
    {
        try {
            $projects = Project::findOrFail($id);
            $projects->delete();
            return redirect()->route('projects')->with('success', 'Reseach Proposal Successfully Deleted!');
        } catch (Exception $e) {
            // Handle the exception, you can log it or return an error response
            return redirect()->route('projects')->with('error', 'An error occurred while deleting the Reseach Proposal: ' . $e->getMessage());
        }
    }

}
