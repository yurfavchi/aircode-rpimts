
<div class="modal fade" id="ProjectTeam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Project Team</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('submission-details.project-teams.store', ['id' => $records->id]) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $records->id }}">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="member_name">Name</label>
                            <input type="text" class="form-control" id="member_name" name="member_name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="role">Role</label>
                            <input type="text" class="form-control" id="role" name="role" required>
                            <!-- <select class="form-control" id="role" name="role" required>
                                <option disabled selected>Select Role</option>
                                <option>Project Leader</option>
                                <option>Database Designer</option>
                                <option>Network Designer</option>
                                <option>UI Designer</option>
                                <option>Quality Assurance</option>
                                <option>Document Writer</option>
                            </select> -->
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 
