<!--<script type="text/javascript">-->
$('.compare-p').hide();
    var compare='<?php echo site_url('ajax/cmp'); ?>';
    $(document).on('click','.compare',function(){
        $('._ld').show();
        $.post(compare,{action:'_atc','id':$(this).attr('pid')},function(r){ r=$.parseJSON(r);
            if(r.status)
            _c2htm(r.products);
            else{ $('._ld').hide(); alert(r.msg)}
        });
    });
    
    $(document).on('click','.cpX',function(){
    $this=$(this);$('._ld').show();
        $.post(compare,{action:'_dtc','id':$(this).attr('pid')},function(r){ r=$.parseJSON(r);
        if(r.status)
            _c2htm(r.products);
        });
    });
    
    <?php if($this->session->userdata('_ck')): ?>
        $.post(compare,{action:'_ftc'},function(r){ r=$.parseJSON(r);
            _c2htm(r.products);
        });
    <?php endif; ?>
    _c2htm=function(products){
    var html = '';
    var hrf = '<?php echo site_url('compare'); ?>/';
        for(i in products){
            var p=products[i];
            html += '<li>';
<!--            html += '<a href="">';-->
            html += '<div class="img">';
            html += '  <img height="50" alt="" src="'+p.image+'">';
            html += '</div>';
            html += '<span>'+p.name+'</span>'
<!--            html += '</a>';-->
            html += '<div pid="'+p.product_id+'" class="close cpX">X</div>';
            html += '</li>';
            hrf += p.slug+'-vs-';
        }
        hrf = hrf.substring(0, hrf.length - 4);
        hrf += '-mobile';
        
        if(products.length>1)
        $('#cmpNw').attr('href',hrf);
        else
        $('#cmpNw').attr('href','#');
        $('#cmpLS').html(html);
        $('._ld').hide();
        
        if(!products.length){
             $('.compare-p').hide();
        }else{
            $('.compare-p').show();
        }
    }
    $(document).ready(function(){
        $('#cmpNw').click(function(e){ e.preventDefault();
            if($(this).attr('href')!='#')
            window.open($(this).attr('href'), '_blank');
            else
            alert('Please add one more product to compare with.')
         });
    })
    
<!--</script>-->