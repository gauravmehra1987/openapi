<h2 class="bh"><span><?php echo str_replace('-',' ', $term); ?></span></h2>

<div class="breadcrumb">
    <a href="<?php echo base_url(); ?>">Home</a> » Search <i><?php echo str_replace('-',' ', $term); ?></></div>

<div id="sS" class="wd">
<form method="post" action="<?php echo site_url('search'); ?>">
                        <span class="drop"></span>
                        <div class="select-menu">
                            <div class="checkbox">
                            <input id="check1" type="checkbox" name="check" value="check1"/>  
                            <label for="check1">Checkbox No. 1</label>
                            <span class="clr"></span>                            
                            <input id="check2" type="checkbox" name="check" value="check2"/>  
                            <label for="check2">Checkbox No. 2</label>
                            <span class="clr"></span> 
                            
                            <input id="check1" type="checkbox" name="check" value="check1"/>  
                            <label for="check1">Checkbox No. 1</label>
                            <span class="clr"></span>                            
                            <input id="check2" type="checkbox" name="check" value="check2"/>  
                            <label for="check2">Checkbox No. 2</label>
                            <span class="clr"></span> 
                            <input id="check1" type="checkbox" name="check" value="check1"/>  
                            <label for="check1">Checkbox No. 1</label>
                            <span class="clr"></span>                            
                            <input id="check2" type="checkbox" name="check" value="check2"/>  
                            <label for="check2">Checkbox No. 2</label>
                            <span class="clr"></span> 
                            <input id="check1" type="checkbox" name="check" value="check1"/>  
                            <label for="check1">Checkbox No. 1</label>
                            <span class="clr"></span>                            
                            <input id="check2" type="checkbox" name="check" value="check2"/>  
                            <label for="check2">Checkbox No. 2</label>
                            
                            </div>
                            
                        </div>
                        <input type="text" class="tf" name="q" value="<?php echo str_replace('-',' ', $term); ?>" placeholder="Search here"/>
                        <input type="submit" value="search" class="s-btn"/>
</form></div>

<?php $this->load->view('product/blocks/compare'); ?>

<div class="pt hide"> 
    <div id="filter">
<div id="slider-range"></div>
<ul>
    <li class="fI"><label for="Device type">Device type</label><span></span>
        <div class="size-options">
            <ul>
                <li><input id="dtBp" type="checkbox" value="Basic phone" name="attr[]" class="filter"><span class="sL">Basic phone</span></li>
                <li><input id="dtFp" type="checkbox" value="Feature phone" name="attr[]" class="filter"><span class="sL">Feature phone</span></li>
                <li><input id="dtSp" type="checkbox" value="Smart phone" name="attr[]" class="filter"><span class="sL">Smart phone</span></li>
                <li><input id="dtTp" type="checkbox" value="Tablet" name="attr[]" class="filter"><span class="sL">Tablet</span></li>
            </ul>
        </div>
    </li>
    
    <li class="fI">
    <label for="Operating System">Operating System</label><span></span>
        <div class="size-options">
            <ul>
                <li><input id="osAnd" type="checkbox" value="Android" name="attr[]" class="filter" /><span class="sL">Android</span></li>
                <li><input id="osWin" type="checkbox" value="Windows" name="attr[]" class="filter" /><span class="sL">Windows</span></li>
                <li><input id="osL" type="checkbox" value="Linux" name="attr[]" class="filter" /><span class="sL">Linux</span></li>
                <li><input id="osOth" type="checkbox" value="Others" name="attr[]" class="filter" /><span class="sL">Others</span></li>
            </ul>
        </div>
    </li>
    <li class="fI">
    <label for="Camera">Camera</label><span></span>
        <div class="size-options">
            <ul>
                <li><input id="3mp" type="checkbox" value="3 megapixel+4 megapixel" name="attr[]" class="filter"/><span class="sL">3.0 Mega Pixel</span></li>
                <li><input id="5mp" type="checkbox" value="5 megapixel" name="attr[]" class="filter"/><span class="sL">5.0 Mega Pixel</span></li>
                <li><input id="8mp" type="checkbox" value="6 megapixel+8 megapixel" name="attr[]" class="filter"/><span class="sL">8 Mega Pixel</span></li>
                <li><input id="9mp" type="checkbox" value="12 megapixel+12.1 megapixel+13 megapixel+16 megapixel" name="attr[]" class="filter"/><span class="sL">8 MP above</span></li>
            </ul>
        </div>
    </li>
    <li class="fI">
    <label for="Screen Size">Screen Size</label><span></span>
        <div class="size-options">
            <ul>
                <li><input id="3inch" type="checkbox" value="3.0 inches+3.1 inches+3.2 inches+3.3 inches+3.4 inches+3.5 inches+3.6 inches+3.7 inches+3.8 inches" name="attr[]" class="filter"/><span class="sL">3 Inch</span></li>
                <li><input id="4inch" type="checkbox" value="4.0 inches+4.1 inches+4.2 inches+4.3 inches+4.4 inches+4.5 inches+4.6 inches+4.7 inches+4.8 inches" name="attr[]" class="filter"/><span class="sL">4 Inch</span></li>
                <li><input id="5inch" type="checkbox" value="5.0 inches+5.1 inches+5.2 inches+5.3 inches+5.4 inches+5.5 inches+5.6 inches+5.7 inches+5.8 inches+5.9 inches" name="attr[]" class="filter"/><span class="sL">5 Inch</span></li>
                <li><input id="6inch" type="checkbox" value="6.0 inches+6.1 inches+6.2 inches+6.3 inches+6.4 inches+7.0 inches+7.1 inches+7.7 inches+7.9 inches+8.0 inches+8.0 inches+8.1 inches+8.2 inches+8.3 inches+8.9 inches" name="attr[]" class="filter"/><span class="sL">5 Inch Above</span></li>
            </ul>
        </div>
    </li>
</ul>
<span style="float: right; padding-left: 20px; display: none" class="ld"><img src="<?php img_src('loader.gif'); ?>" /></span></div>
</div>
<div class="sfil"><ul></ul></div>
<div style="float: left;" class="box bp"><ul id="pls"></ul>    
</div>
<script type="text/javascript">
    var term = '<?php echo $term; ?>';
    var ajax='<?php echo site_url('ajax/pro'); ?>';
    var $page = 1;var oldHeight = 0;var status = true; var reset=false;
reset=function(){
    $page = 1;oldHeight = 0;status = true;reset=false;
}
_pls=function(d,a){
    if(!a)
    $('#pls').html('');
        for(i in d){ item=d[i];
                var li =  '<li id="ls'+item.product_id+'" style="float: left; list-style: none; display:none">'
                li +='    <span class="img"><a href="/'+item.slug+'">'
                li +='        <img src="'+item.thumb+'" /></a></span>'
                li +='        </div>'
                li +='        <div class="info">'
                
                li +='            <div class="name"><a href="/'+item.slug+'">'+item.name+'</div></a>'
                li +='            <div class="price hide">Rs: '+item.price+'</div>'
                li += '                 <div class="bl"><a class="button more" href="/'+item.slug+'"><span>More Info</span></a>';
                li += '                         <a class="compare" pid="'+item.product_id+'" title="Add to Compare">Add to Compare</a>'
                li += '                  </div>'
                li +='        </div>'
                li +='    </div>'
                li +='</li>';

                $('#pls').append(li);
                $('#ls'+item.product_id).fadeIn(400);
       }
}

_r=function(p){sfil();
$('.ld,._ld').show();
    var page=(p.page)?p.page:1;
    var _at=[];
    $('input[name*=\'attr\']:checked').each(function(i){_at[i]=$(this).val();});
    $.post(ajax,{action:'_search','page':page,_s:term,_a:_at},function(r){ r=$.parseJSON(r);
        if(p.a)
        _pls(r.p,true)
        else
            _pls(r.p,false);
        $('.ld,._ld').hide();
    })
}

$(document).ready(function(){

pagi();
_r({page:1});
    $('.filter').click(function(){_r({page:1,a:false})});
});


pagi=function(){
    $(window).scroll(function(){
        var ws = $(window).scrollTop();
        var dh = $(document).height();
        var wh = $(window).height();
        var diff = dh - wh;
        if((diff-ws) < 250 && dh > oldHeight){
            $page+=1;
            _r({'page':$page,a:true});
            oldHeight = $(document).height();
        }

    });
}
sfil=function(){
    var sf={};
    $('.fI').each(function(i){
    if($('.filter:checked',this).length)
        var group = $(this).find('label').text();
        if(group!=undefined){
            var items=[];
            $('.filter:checked',this).each(function(i){
                var th = $(this);
                var text = th.parent().find('.sL').text();
                var id = th.attr('id');
                items.push({_t:text,_id:id});
            });
            sf[group]=items;
        }
    });
    var sfh='';
        for(k in sf){
            sfh += '<li>'
            sfh += '<span class="sfg">'+k+' »</span>'
            sfh += '<ul>';
            for(v in sf[k]){ v=sf[k][v];
                sfh += '    <li>'+v._t+'<a remove="'+v._id+'" class="sfx" title="remove">X</a></li>'
            }
            sfh += '</ul>'
            sfh += '</li>';
            
         }
    $('.sfil').html('<ul>'+sfh+'</ul>');
}

$('.sfil').on('click','.sfx',function(){
    $('#'+$(this).attr('remove')).attr('checked',false).trigger('change');
});


$('input[name=q]').autocomplete({delay: 200,
                       source:function(request, response){
                           $.ajax({
                            url: ajax,
                            data:{action:'_auto',_s:request.term},
                            dataType: 'json',
                            method:'post',
                            success: function(json) {		
                                if(json.p)
                                    response($.map(json.p, function(item) {
                                            return {
                                                    label: item.name,
                                                    value: item.name
                                            }
                                    }));
                            }
                       });
                            
                       },
                        select: function(event, ui) {
                        },
                      focus: function(event, ui) {
                            return false;
                      }
                   })


</script>
