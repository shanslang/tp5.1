$("#p_cardType").change(function(){
    var cType = jQuery(this).val();
    var index = jQuery('select option[value="'+cType+'"]').index();
    jQuery.ajax({url:'/index.php/admin/Setmultiplex/globalLivcard', type: 'post', dataType: 'json'}).done(function(data){
        jQuery("#c_price").html(data[index]['CardPrice']);
        jQuery("#hi_price").val(data[index]['CardPrice']);
        jQuery("#p_diamondCt").val(data[index]['CardPrice']);
    });

});
