<!-- Print Options Modal -->
<div id="printOptionsModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Print Attendance</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="printOptionsForm">
                    <div class="form-group">
                        <label for="empCodeSelect">Employee Code</label>
                        <select id="empCodeSelect" class="form-control select2" multiple="multiple">
                            <option value="all">All</option>
                            <!-- Add employee codes dynamically -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fromDate">From Date</label>
                        <input type="text" id="fromDate" class="form-control datepicker" placeholder="MM-YYYY">
                    </div>
                    <div class="form-group">
                        <label for="toDate">To Date</label>
                        <input type="text" id="toDate" class="form-control datepicker" placeholder="MM-YYYY">
                    </div>
                    <button type="button" class="btn btn-primary" id="submitPrintOptions">Print</button>
                </form>
            </div>
        </div>
    </div>
</div>