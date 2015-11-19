<h1><?php echo $category->name; ?></h1>
<div class="pt"><div id="filter">
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
<span style="float: right; padding-left: 20px; display: none" class="ld"><img src="<?php img_src('loader.gif'); ?>" /></span>
    </div>
</div>
<div class="sfil">
    <ul>

    </ul>
</div>



<div style="float: left;" class="box bp">
    <ul id="pls">
        <?php foreach($products as $product): ?>
        <li class="hide"><span class="img"><a href="<?php echo site_url($product->slug); ?>"><img src="<?php echo $product->thumb; ?>"></a></span><div class="info"><div class="name"><a href="/<?php echo $product->slug; ?>"><?php echo $product->name; ?></a></div><div class="price hide"><?php echo $product->price; ?></div><div class="bl hide"><a href="/<?php echo $product->slug; ?>" class="button more"><span>More Info</span></a><a title="Add to Compare" pid="1" class="compare">Add to Compare</a></div></div></li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="desc">
   <?php echo html_entity_decode($category->description); ?>
</div>

<script type="text/javascript">
    var c = <?php echo json_encode($category); ?>;
//    var p = <?php //echo json_encode($products); ?>;
    var pg = <?php echo json_encode($pagi); ?>;
    var ajax = '<?php echo site_url('ajax/cls') ?>';
    var $page = 1;var oldHeight = 0;var status = true; var reset=false;
    
    
    
reset=function(){
    $page = 1;oldHeight = 0;status = true;reset=false;
}
_pls=function(d,a){
    if(!a)
    $('#pls').html('');
        for(i in d){ var item=d[i];
                var li =  '<li id="ls'+item.product_id+'" style="float: left; list-style: none; display:none">'
                li +='    <span class="img"><a href="/'+item.slug+'">'
                li +='    <div>'
                li +='        <img src="'+item.thumb+'" /></a></span>'
                
                li +='        <div class="info">'
                
                li +='            <div class="name"><a href="/'+item.slug+'">'+item.name+'</a></div>'
                li +='            <div class="price hide">Rs: '+item.price+'</div>'
                li += '                 <div class="bl hide"><a class="button more" href="/'+item.slug+'"><span>More Info</span></a>';
                li += '                         <a class="compare" pid="'+item.product_id+'" title="Add to Compare">Add to Compare</a>'
                li += '                  </div>'
                li +='        </div>'
                li +='    </div>'
                li +='</li>';
                
                $('#pls').append(li);
                //document.getElementById('pls').innerHTML=li;
                $('#ls'+item.product_id).fadeIn(400);
       }
} 
var pg=false;
_r=function(p){
sfil();
    $('.ld,._ld').show();
    var page=(p.page)?p.page:1;
    var _at=[];
    $('input[name*=\'attr\']:checked').each(function(i){_at[i]=$(this).val();});
    $.post(ajax,{action:'_ls','page':page,_c:c.category_id,_a:_at},function(r){ r=$.parseJSON(r);
        if(p.a){
        _pls(r.p,true);pg=r.pg;
        }else{
            _pls(r.p,false);pg=r.pg;
        }
        $('.ld,._ld').hide();
    })
}

$(document).ready(function(){
pagi();
_r({page:1});
$('.filter').change(function(){_r({page:1,a:false})});
});


pagi=function(){
    $(window).scroll(function(){
        var ws = $(window).scrollTop();
        var dh = $(document).height();
        var wh = $(window).height();
        var diff = dh - wh;
        if((diff-ws) < 250 && dh > oldHeight){
//            console.log(pg)
            if(pg.pages>1){
                $page+=1;
                _r({'page':$page,a:true});
                
            }
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

</script>