 <!doctype html>
<html lang="en">
<?
include 'function.php';
$deal = json_decode($_REQUEST['PLACEMENT_OPTIONS'], true)['ID'];
?>
<head>
    <title>Title</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<input type="hidden" id="deal" value="<?= $deal ?>">
<div class="container">
    <div class="row">
        <div class="col-12">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addServices">Добавить услугу</button>
            <div class="product-table">
<!--                <div class="services-block" data-rows="1">-->
<!--                    <select class="form-select services" id="services">-->
<!--                        <option selected value="">выберите услугу</option>-->
<!--                        --><?// foreach (servicesProduct() as $item): ?>
<!--                            <option value="--><?//= $item['id'] ?><!--">--><?//= $item['name'] ?><!--</option>-->
<!--                        --><?// endforeach; ?>
<!--                    </select>-->
<!--                    <div class="products-block">-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>
        <div class="col-12">
            <button class="btn btn-success float-end btn-sm mt-3" id="save">Сохранить</button>
<!--            <button class="btn btn-primary mt-3 float-end btn-sm me-3" id="plus-rows"><i class="fa-solid fa-cart-plus"></i></button>-->
        </div>
    </div>
</div>
<div class="modal fade" id="addServices" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Добавить услуги</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <? foreach (servicesProduct() as $item): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="services" value="<?= $item['id'] ?>" id="<?= $item['id'] ?>">
                        <label class="form-check-label" for="<?= $item['id'] ?>"><?= $item['name'] ?></label>
                    </div>
                <? endforeach; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="add-services">Добавить</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addProducts" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Добавить товары</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="listProduct">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="add-product">Добавить</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addMaterials" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Добавить материалы</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="listMaterial">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="add-material">Добавить</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="//api.bitrix24.com/api/v1/"></script>
<script src="https://kit.fontawesome.com/b675a8d36a.js" crossorigin="anonymous"></script>
<script src="script.js"></script>
</body>

</html>