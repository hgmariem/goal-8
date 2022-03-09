<!-- Modal -->
<div id="myModal" class="modal fade habit-per-graph" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Habit Progress Statistics
                    <span style="margin-left:5px;">(
    <a  class="grapthnprev" onclick="display_next_graph()"  id="next_graph">Prev</a>
        <a class="grapthnprev" onclick="display_prev_graph()" id="prev_graph">Next</a>  )
    </span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="loader-section" style="display:none;">
                    <img src="./img/ajax-loader.gif" />
                </div>
                <div class="modal-body-data">
                </div>
                <!--<p>Some text in the modal.</p>-->

            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-default closem10" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<div id="myLinechartModal" class="modal fade habit-per-linegraph" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Habit Progress Statistics
                    <!-- <span style="margin-left:5px;">(
    <a  class="grapthnprev" onclick="display_next_graph()"  id="next_graph">Prev</a>
        <a class="grapthnprev" onclick="display_prev_graph()" id="prev_graph">Next</a>  )
    </span> -->
    </h4>
    <div class="graph text-right">
        <label>Graph</label>
        
    </div>
                
            </div>
            <div class="modal-body">
                <div class="loader-section" style="display:none;">
                    <img src="./img/ajax-loader.gif" />
                </div>
                <div class="modal-body-selection-data">
                </div>
                <div class="modal-body-data">
                </div>
                <!--<p>Some text in the modal.</p>-->

            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-default closem10" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>