<div class="modal fade" id="modal-feedback" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title">Feedback & Bug Reports</h4>
            </div>
            <div class="modal-body">
                <p>Here you can suggest new features or improvements for Eve Trade Master. In case of bug reports, try to be specific.</p>
                <div class="form-group">
                    <form class="submit-feedback" method="POST">
                        <input type="hidden" name="email" value="<?=$email?>">
                        <input type="hidden" name="from_name" value="<?=$username?>">
                        <input type="hidden" name="to" value="etmdevelopment42@gmail.com">
                        <input type="hidden" name="subject" value="New Message from <?=$username?>">
                        <textarea class="form-control" rows="3" name="message" autofocus></textarea>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-accent btn-send-feedback">Send</button>
            </div>
        </div>
    </div>
</div>