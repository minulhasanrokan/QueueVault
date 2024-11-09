<style type="text/css">
    .loading {
        z-index: 999999999999999;
        position: absolute;
        top: 0;
        left:-5px;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.4);
    }
    .loading-content {
        position: absolute;
        border: 16px solid #f3f3f3;
        border-top: 16px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        top: 40%;
        left:50%;
        animation: spin 1s linear infinite;
    }
          
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .dataTable tbody tr:hover {
        background-color: #007bff47 !important;
    }

    .dataTable tbody tr td {
        max-width: 100px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }

    .menu-group-bg-color{
        background-color: #3f6791 !important;
    }

    .sub-menu-bg-color{
        background-color: green !important;
    }

    .menu-bg-color{
        background-color: #3f6791 !important;
    }

    .error {
        display: none;
        color: red;
    }

    #page_content{
        padding: 10px;
    }

    .span_red{
        color: red;
    }

    .span_green{
        color: green;
    }

    .background_color_green{
        background-color: #0069d9;
    }
    .staticBackdrop_modal{
        top: 10px;
    }
    #modal_body{
        min-height: 100px;
        max-height: 550px !important;
        overflow-y: scroll !important;
    }
    .custom-modal{
        top: 100px;
    }
    #right_view_modal_body{
        min-height: 100px;
        max-height: 550px !important;
        overflow-y: scroll !important;
    }
    .activity_history_modal{
        top: 100px;
    }
    #activity_history_modal_body{
        min-height: 100px;
        max-height: 550px !important;
        overflow-y: scroll !important;
    }
    .history_modal{
        top: 10px;
    }
    #history_modal_body{
        min-height: 100px;
        max-height: 550px !important;
        overflow-y: scroll !important;
    }

    .text_boxes_numeric
    {
        text-align:right;
    }

    .modal-header {
      background-color: #0069d9;
      color: white;
    }

    .ui-datepicker{
        z-index: 10 !important;
    }
</style>
<nav class="main-header navbar navbar-expand navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!--details View Modal-->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog staticBackdrop_modal" role="document" id="modal_dialog">
        <div class="modal-content" id="modal_content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
                <button type="button" onclick="hide_modal();" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body"></div>
        </div>
    </div>
</div>
<!--popup confirm Modal-->
<div class="modal fade" id="custom-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modal-head" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">  
        <div class="modal-content custom-modal">  
            <!-- modal-header -->  
            <div class="modal-header" id="modal-head"></div>  
            <!-- modal-body -->  
            <div class="modal-body" id="modal-body"></div>  
            <!-- modal-footer -->  
            <div class="modal-footer" id="modal-footer"></div>
        </div>  
    </div>
</div>
<!--right View Modal-->
<div class="modal fade" id="right_view_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="right_view_modal_title" aria-hidden="true">
    <div class="modal-dialog" role="document" id="right_modal_dialog">
        <div class="modal-content" id="right_modal_content">
            <div class="modal-header">
                <h5 class="modal-title" id="right_view_modal_title"></h5>
                <button type="button" onclick="hide_right_modal();" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="right_view_modal_body"></div>
        </div>
    </div>
</div>
<!--activity history View Modal-->
<div class="modal fade" id="activity_history_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="activity_history_modal_title" aria-hidden="true">
    <div class="modal-dialog" role="document" id="activity_history_modal_dialog">
        <div class="modal-content activity_history_modal" id="activity_history_modal_content">
            <div class="modal-header">
                <h5 class="modal-title" id="activity_history_modal_title"></h5>
                <button type="button" onclick="hide_activity_history_modal();" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="activity_history_modal_body"></div>
        </div>
    </div>
</div>
<!--history View Modal-->
<div class="modal fade" id="history_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="history_modal_title" aria-hidden="true">
    <div class="modal-dialog" role="document" id="history_modal_dialog">
        <div class="modal-content history_modal" id="history_modal_content">
            <div class="modal-header">
                <h5 class="modal-title" id="history_modal_title"></h5>
                <button type="button" onclick="hide_history_modal();" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="history_modal_body"></div>
        </div>
    </div>
</div>