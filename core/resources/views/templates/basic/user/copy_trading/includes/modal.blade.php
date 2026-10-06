<div class="modal fade" id="buyCopyTradeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{route('user.buy.classic.trade')}}" method="post">
                @csrf
                <input type="hidden" name="id" class="copy_trade_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Confirm</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-4 pb-4">
                    <div>
                        <h5>Are you sure you want to copy <strong style="color: #0cba0c;padding: 0px 5px;" class="trade_name"></strong> trade?</h5>
                    </div>

                    <div class="details-section">
                        <h5 class="mb-3 text-danger">
                            You Will Get Interest <span class="final-interest">00</span>   <strong class="interest_type_t">0d</strong>
                        </h5>
                        <h6>ROI Will Get Every: <strong class="interest_type">0d</strong></h6>
                        <h6>Trade Cost: <strong class="cost">0d</strong></h6>
                        <h6>Trade Interest: <strong class="interest">0d</strong></h6>
                        <h6>Wallet Balance: <strong class="current-balance">0.00</strong></h6>
                    </div>
                    <div class="mt-3 main-trade-note-section">
                        <h6>Details</h6>
                        <div class="trade-note-section">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-confirm">Confirm</button>
                </div>

            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="withdrawCopyTradeModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{route('user.withdraw.classic.trade')}}" method="post">
                @csrf
                <input type="hidden" name="id" class="w_copy_trade_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Confirmation</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-4 pb-4">
                    <div>
                        <h5>Are you sure you want to withdraw <strong style="color: #0cba0c;padding: 0px 5px;"
                                                                      class="w_trade_name"></strong> trade?</h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-confirm">Confirm</button>
                </div>

            </form>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="mobileViewModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="staticBackdropLabel">Warning</h5>
                <button type="button" class="close d-none" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <h4 class="text-success">This feature only for mobile device</h4>
            </div>

        </div>
    </div>
</div>
