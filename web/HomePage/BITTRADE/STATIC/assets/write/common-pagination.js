    var htmlWriteFunc;
    var htmlScrollTop = 300;
    
    var htmlWriteFunc2;
    var htmlScrollTop2 = 300;

    // pager init
    function init_pager(html_callback,html_scroll_top){
        if(typeof html_callback === 'function'){
            htmlWriteFunc = html_callback;
        }
        if(typeof html_scroll_top != 'undefined'){
            htmlScrollTop = html_scroll_top;
        }

        $('#pager').html('');
        var pagerview = '';
        var page = '';

        // 처음으로 <<
        pagerview += '<a href="javascript:goPager(1)" alt="First"><i class="xi-angle-left-thin"></i><i class="xi-angle-left-thin"></i></a>';

        // 전 블록 <
        if( listPageSet.current_block > 1 ){
            page = listPageSet.current_page - listPageSet.page_block;
            if(page < 1 ) page = 1;
            pagerview += '<a href="javascript:goPager('+page+')" alt=""><i class="xi-angle-left-thin"></i></a>';
        }else{
            pagerview += '<a href="javascript:goPager(1)"><i class="xi-angle-left-thin"></i></a>';
        }

        // 해당 블럭 페이징 넘버
        // 시작 페이지, 끝 페이지
        var start_page  = ((listPageSet.current_block-1)*listPageSet.page_block)+1;
        var end_page    = start_page +(listPageSet.page_block-1);   // 10 -1
        if(end_page > listPageSet.total_page){
            end_page    = listPageSet.total_page;
        }
        var i           = start_page;
        if(listPageSet.total_page>0){
            for(i; i<=end_page; i++){
                if(i === listPageSet.current_page){
                    pagerview += '<a href="javascript:goPager('+i+')" class="active">'+i+'</a>';
                }else{
                    pagerview += '<a href="javascript:goPager('+i+')">'+i+'</a>';
                }
            }
        }else{
            // 페이징 total_page == 0
            pagerview += '<a href="javascript:goPager(1)"  class="active">1</a>';
        }

        // 다음 블록 >
        if( listPageSet.current_block < listPageSet.total_block ){
             var tmppage = calfloat('CEIL', (listPageSet.current_block * listPageSet.page_block), 0) + 1;
//             if(tmppage > listPageSet.total_page) tmppage = listPageSet.total_page;
            pagerview += '<a href="javascript:goPager('+tmppage+');"><i class="xi-angle-right-thin"></i></a>';
        }else{
            pagerview += '<a href="javascript:goPager('+listPageSet.total_page+');"><i class="xi-angle-right-thin"></i></a>';
        }

        // 마지막으로 >>
        if(listPageSet.total_page>0){
            pagerview += '<a href="javascript:goPager('+listPageSet.total_page+')" alt="Last"><i class="xi-angle-right-thin"></i><i class="xi-angle-right-thin"></i></a>';
        }else{
            pagerview += '<a href="javascript:goPager(1)" alt="Last"><i class="xi-angle-right-thin"></i><i class="xi-angle-right-thin"></i></a>';
        }

        $('#pager').html(pagerview);
    }
    
    // pager init second - 한페이지에서 두개의 페이징이 필요한 경우 사용
    function init_pager_second(html_callback,html_scroll_top){
        if(typeof html_callback === 'function'){
            htmlWriteFunc2 = html_callback;
        }
        if(typeof html_scroll_top != 'undefined'){
            htmlScrollTop2 = html_scroll_top;
        }

        $('#pager_second').html('');
        var pagerview = '';
        var page = '';

        // 처음으로 <<
        pagerview += '<a href="javascript:goPager_second(1)" alt="First"><<</a>';

        // 전 블록 <
        if( listPageSet2.current_block > 1 ){
            page = listPageSet2.current_page - listPageSet2.page_block;
            if(page < 1 ) page = 1;
            pagerview += '<a href="javascript:goPager_second('+page+')" alt=""><</a>';
        }else{
            pagerview += '<a href="javascript:goPager_second(1)"><</a>';
        }

        // 해당 블럭 페이징 넘버
        // 시작 페이지, 끝 페이지
        var start_page  = ((listPageSet2.current_block-1)*listPageSet2.page_block)+1;
        var end_page    = start_page +(listPageSet2.page_block-1);   // 10 -1
        if(end_page > listPageSet2.total_page){
            end_page    = listPageSet2.total_page;
        }
        var i           = start_page;
        if(listPageSet2.total_page>0){
            for(i; i<=end_page; i++){
                if(i === listPageSet2.current_page){
                    pagerview += '<a href="javascript:goPager_second('+i+')" class="active">'+i+'</a>';
                }else{
                    pagerview += '<a href="javascript:goPager_second('+i+')">'+i+'</a>';
                }
            }
        }else{
            // 페이징 total_page == 0
            pagerview += '<a href="javascript:goPager_second(1)"  class="active">1</a>';
        }

        // 다음 블록 >
        if( listPageSet2.current_block < listPageSet2.total_block ){
             var tmppage = calfloat('CEIL', (listPageSet2.current_block * listPageSet2.page_block), 0) + 1;
//             if(tmppage > listPageSet.total_page) tmppage = listPageSet.total_page;
            pagerview += '<a href="javascript:goPager_second('+tmppage+');">></a>';
        }else{
            pagerview += '<a href="javascript:goPager_second('+listPageSet2.total_page+');">></a>';
        }

        // 마지막으로 >>
        if(listPageSet2.total_page>0){
            pagerview += '<a href="javascript:goPager_second('+listPageSet2.total_page+')" alt="Last">>></a>';
        }else{
            pagerview += '<a href="javascript:goPager_second(1)" alt="Last">>></a>';
        }

        $('#pager_second').html(pagerview);
    }



    var bodydom = $('html ,body');
    // move to pager
    function goPager(page){
        listPageSet.current_page    = parseInt(page);
        listPageSet.current_block   = calfloat('CEIL', (listPageSet.current_page)/listPageSet.page_block, 0);

        if(typeof htmlWriteFunc === 'function'){
            htmlWriteFunc(listPageSet.current_page);
        }else{
            list_content(listPageSet.current_page);
        }

        init_pager();
        if(htmlScrollTop!=false) bodydom.animate( {scrollTop:htmlScrollTop}, 300 );
    }
    
    function goPager_second(page){
        listPageSet2.current_page    = parseInt(page);
        listPageSet2.current_block   = calfloat('CEIL', (listPageSet2.current_page)/listPageSet2.page_block, 0);

        if(typeof htmlWriteFunc2 === 'function'){
            htmlWriteFunc2(listPageSet2.current_page);
        }else{
            list_content(listPageSet2.current_page);
        }

        init_pager_second();
        if(htmlScrollTop2!=false) bodydom.animate( {scrollTop:htmlScrollTop2}, 300 );
    }