$(document).ready(function () {
    $(document).on('change', '#services', function () {
        let parent = $(this).parents('div').attr('data-rows')
        $.ajax({
            url: './function.php',
            method: 'post',
            dataType: 'html',
            data: {id: $(this).val(), 'event': 'print-product', parent: parent},
            success: function (data) {
                $('div[data-rows="' + parent + '"] .products-block').html(data)
            }
        });
    })

    $(document).on('click', '#add-services', function () {
        var checked = [];
        $('input[name="services"]:checked').each(function () {
            checked.push($(this).val());
        });
        $.ajax({
            url: './function.php',
            method: 'post',
            dataType: 'html',
            data: {id: checked, 'event': 'list-services'},
            success: function (data) {
                $('.product-table').html(data)
                $('#addServices').modal('hide')
            }
        });
    })
    $(document).on('click', '#btn-products', function () {
        $('#add-product').attr('data-id', $(this).attr('data-services'))
        $('#add-product').attr('parent', $(this).attr('parent'))
        $.ajax({
            url: './function.php',
            method: 'post',
            dataType: 'html',
            data: {'event': 'list-products'},
            success: function (data) {
                $('#listProduct').html(data)
            }
        });
    })
    $(document).on('click', '#add-product', function () {
        var checked = [];
        $('input[name="products"]:checked').each(function () {
            checked.push($(this).val());
        });
        let parent = $(this).attr('parent')
        $('div[data-count="' + parent + '"] .list-products').html('')
        $.ajax({
            url: './function.php',
            method: 'post',
            dataType: 'html',
            data: {id: checked, parent: parent, 'event': 'print-services'},
            success: function (data) {
                data = JSON.parse(data)
                $(data.html).insertAfter('div[data-count="' + parent + '"] .list-services')
                $('#addProducts').modal('hide')
            }
        });
    })
    $(document).on('click', '#btn-materials', function () {
        $('#add-material').attr('data-id', $(this).attr('data-services'))
        $('#add-material').attr('parent', $(this).attr('parent'))
        $.ajax({
            url: './function.php',
            method: 'post',
            dataType: 'html',
            data: {'event': 'list-material'},
            success: function (data) {
                $('#listMaterial').html(data)
            }
        });
    })
    $(document).on('click', '#add-material', function () {
        var checked = [];
        $('input[name="material"]:checked').each(function () {
            checked.push($(this).val());
        });
        let parent = $(this).attr('parent')
        $('div[data-count="' + parent + '"] .list-materials').html('')
        $.ajax({
            url: './function.php',
            method: 'post',
            dataType: 'html',
            data: {id: checked, parent: parent, 'event': 'print-materials'},
            success: function (data) {
                data = JSON.parse(data)
                $(data.html).insertAfter('div[data-count="' + parent + '"] .list-services')
                $('#addMaterials').modal('hide')
            }
        });
    })
    $(document).on('click', '#delete-products', function () {
        let parent = $(this).attr('parent')
        let id = $(this).attr('data-id')
        $('div[data-count="' + parent + '"] .list-products[id="'+id+'"]').remove()
    })
    $(document).on('click', '#delete-material', function () {
        let parent = $(this).attr('parent')
        let id = $(this).attr('data-id')
        $('div[data-count="' + parent + '"] .list-materials[id="'+id+'"]').remove()
    })
    $(document).on('click', '.delete-services', function (){
        let parent = $(this).attr('data-count')
        $('.services-block[data-count="' + parent + '"]').remove()
    })

    $(document).on('click', '#save', function () {
        let arr = []
        let serviceVal, productVal
        $(".form-control").each(function (item) {
            let count = item + 1
            let summ = 0
            $('.form-label[parent="'+count+'"]').each(function () {
                summ += parseInt($(this).attr('data-summ'))
            })
            arr.push({
                name: $(this).val(),
                id: $(this).attr('data-id'),
                summ: summ
            })
        });
        $.ajax({
            url: './function.php',
            method: 'post',
            dataType: 'html',
            data: {
                arr: arr,
                'event': 'save-product',
                deal: $('#deal').val()
            },
            success: function (data) {
                console.log(data)
                // $('#products').html(data)
            }
        });
    })

    $(document).on('click', '#plus-rows', function () {
        let count = $('.services-block').length
        $.ajax({
            url: './function.php',
            method: 'post',
            dataType: 'html',
            data: {count: count, 'event': 'add-rows'},
            success: function (data) {
                $(data).insertAfter('div[data-rows="' + count + '"]')
            }
        });
    })
    $(document).on('click', '#delete-rows', function () {
        let parent = $(this).parents('div').attr('data-rows')
        $('div[data-rows="' + parent + '"]').remove()
    })
    // BX24.callMethod('placement.bind', {
    //     PLACEMENT: 'CRM_DEAL_DETAIL_TAB',
    //     HANDLER: "https://b24-alcoclinic.ru/local/app/moduleWareAccounting/",
    //     TITLE: 'Складской учет',
    //     DESCRIPTION: 'Складской учет'
    // });
})