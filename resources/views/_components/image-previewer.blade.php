<div id="image-editor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Preview and Crop Image</h4>
            </div>
            <div class="modal-body">
                <div class="text-center" style="height: 100%">
                    <img src="" alt="" class="cropper-preview" id="cropper-preview"/>
                </div>
            </div>
            <div class="modal-footer row">
                <div class="text-center col-12">
                    <button class="btn btn-sm btn-primary" title="Zoom In" id="zoom-in">
                        <i class="fa fa-search-plus"></i>
                    </button>
                    <button class="btn btn-sm btn-primary" title="Zoom Out" id="zoom-out">
                        <i class="fa fa-search-minus"></i>
                    </button>
                    <button class="btn btn-sm btn-primary" title="Reset" id="reset">
                        <i class="fa fa-refresh"></i>
                    </button>
                    <button class="btn btn-sm btn-primary" title="Move Image Left" id="ml">
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <button class="btn btn-sm btn-primary" title="Move Image Right" id="mr">
                        <i class="fa fa-chevron-right"></i>
                    </button>
                    <button class="btn btn-sm btn-primary" title="Move Image Up" id="mu">
                        <i class="fa fa-chevron-up"></i>
                    </button>
                    <button class="btn btn-sm btn-primary" title="Move Image Down" id="md">
                        <i class="fa fa-chevron-down"></i>
                    </button>
                </div>
                <hr class="divider"/>
                <!--loader-->
                <div class="text-center col-12">
                    <?php
                    $loader_id = 'up-loader';
                    $loader_text = 'Crunching image...<br/>'
                        .'<span class="msg hidden">This sometimes takes a while, please be patient.</span>';
                    ?>
                    <div id="{{$loader_id ?? 'loader'}}" style="display: none"
                         class="text-center {{$loader_classes ?? 'padding-top-1em'}}">
                        <img src="{{asset('images/defaults/ajax-loader.gif')}}" style="height: 32px"/>
                        @if(empty($loader_text_off))
                            <p class="text-center no-margin {{$loader_font ?? 'font-lg grey-text'}}">
                                {!!$loader_text ?? 'Loading...'!!}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="text-center col-12">
                    <button class="btn btn-success" id="save">Save</button>
                    <button type="button" class="close btn btn-phoenix-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; Close</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
