<div class="sc_section custom_bg_2">
    <div class="content_wrap">
        <div class="sc_empty_space" data-height="2em"></div>
        <div class="sc_blogger layout_classic_3 template_masonry no_padding_post margin_top_huge margin_bottom_huge sc_blogger_horizontal">
            <h2 class="sc_blogger_title sc_item_title">Our Blog Posts</h2>
            <div class="sc_blogger_descr sc_item_descr">
                ...
            </div>
            <div class="isotope_wrap" data-columns="3">
                @foreach($recent_posts as $post)
                    <div class="isotope_item isotope_item_classic isotope_item_classic_3 isotope_column_3">
                        <div class="post_item post_item_classic post_item_classic_3 post_format_standard">
                            <div class="post_featured">
                                <div class="post_thumb">
                                    <a class="hover_icon hover_icon_link" href="{{$post->url}}"><img alt="" src="{{$post->thumb_url}}"></a>
                                </div>
                                <div class="cat_post_info">
                                    <span class="post_categories"><a class="category_link">{{$post->categoriesStr()}}</a></span>
                                </div>
                            </div>
                            <div class="post_content isotope_item_content">
                                <h5 class="post_title"><a href="{{$post->url}}">{{$post->title}}</a></h5>
                                <div class="post_info">
                                            <span class="post_info_item">
                                                <a class="post_info_date" href="{{$post->url}}">{{$post->published_at}}</a>
                                            </span>
                                </div>
                                <div class="post_descr">
                                    <p>{{$post->abstract}}</p>
                                    <a class="post_readmore readmore" href="{{$post->url}}">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.isotope_item -->
                @endforeach
            </div>
            <div class="sc_blogger_button sc_item_button">
                <a class="sc_button sc_button_style_border sc_button_size_medium" href="{{route('cms.post.index')}}">View More Posts</a>
            </div>
        </div>
        <div class="sc_empty_space" data-height="2.3em"></div>
    </div>
</div>
