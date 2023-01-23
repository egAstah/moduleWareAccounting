<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
CModule::IncludeModule("crm");

function servicesProduct()
{
    $arSelect = array("*", 'PROPERTY_*');
    $arFilter = array("IBLOCK_ID" => 14, "ACTIVE" => "Y");
    $iblock = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
    $result = [];
    while ($ob = $iblock->GetNextElement()) {
        $arFields = $ob->GetFields();
        $result[] = [
            'id' => $arFields['ID'],
            'name' => $arFields['NAME']
        ];
    }
    return $result;
}

function printProduct($id)
{
    $arSelect = array("*", 'PROPERTY_*');
    $arFilter = array("IBLOCK_ID" => 14, "ACTIVE" => "Y", 'ID' => $id);
    $iblock = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
    $result = [];
    while ($ob = $iblock->GetNextElement()) {
        $arProps = $ob->GetProperties();
        $result[] = $arProps['151']['VALUE'];
    }
    $html = '';
    foreach ($result[0] as $item) {
        $arFilter = array("IBLOCK_ID" => 40, "ACTIVE" => "Y", 'ID' => $item);
        $iblock = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        while ($ob = $iblock->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arProps = $ob->GetProperties();
            $idProducts = $arFields['ID'];
            $html .= '
                <div class="form-check">
                  <input class="form-check-input" data-service="' . $id . '" type="checkbox" name="products" value="' . $arFields['ID'] . '" id="' . $arFields['ID'] . '_' . $_POST['parent'] . '">
                  <label class="form-check-label" for="' . $arFields['ID'] . '_' . $_POST['parent'] . '">
                    ' . $arFields['NAME'] . '
                  </label>
                </div>
                <div class="materials-block">
            ';
            $arFilter = array("IBLOCK_ID" => 41, "ACTIVE" => "Y", 'ID' => $arProps['152']['VALUE']);
            $iblock = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
            while ($ob = $iblock->GetNextElement()) {
                $arFields = $ob->GetFields();
                $html .= '
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" data-service="' . $id . '" data-products="' . $idProducts . '" name="materials" value="' . $arFields['ID'] . '" id="' . $arFields['ID'] . '_' . $_POST['parent'] . '">
                        <label class="form-check-label" for="' . $arFields['ID'] . '_' . $_POST['parent'] . '">' . $arFields['NAME'] . '</label>
                    </div>
                ';
            }
            $html .= '</div>';
        }
    }

    echo $html;
}

function summProduct($arMaterials)
{
    $summ = 0;
    foreach ($arMaterials as $item) {
        $db_res = CCatalogProduct::GetList(
            array("QUANTITY" => "DESC"),
            array("ID" => $item),
            false,
            []
        );
        while ($ar_res = $db_res->Fetch()) {
            if ($item != 0) $summ += $ar_res['PURCHASING_PRICE'];
        }
    }
    return $summ;
}

switch ($_POST['event']) {
    case 'list-services':
//        if ($_POST['id'] == 0) echo '';
//        else printProduct($_POST['id']);
        $html = '';
        $count = 0;
        foreach ($_POST['id'] as $item) {
            $db_res = CCatalogProduct::GetList(
                array("QUANTITY" => "DESC"),
                array("ID" => $item),
                false,
                []
            );
            $count++;
            while ($ar_res = $db_res->Fetch()) {
                $html .= '
                <div class="services-block mt-3" data-count="' . $count . '">
                    <div class="d-flex align-items-end list-services">
                        <div class="me-3">
                            <label class="form-label">Название</label>
                            <input type="text" data-id="' . $ar_res['ID'] . '" class="form-control me-3 w-100" id="services-val" value="' . $ar_res['ELEMENT_NAME'] . '" id="' . $ar_res['ID'] . '">
                        </div>
                        <button class="btn btn-warning btn-sm me-3" data-bs-toggle="modal" id="btn-products" parent="' . $count . '" data-bs-target="#addProducts" data-services="' . $ar_res['ID'] . '">Добавить товар</button>
                        <button class="btn btn-success btn-sm me-3" data-bs-toggle="modal" id="btn-materials" parent="' . $count . '"  data-bs-target="#addMaterials" data-services="' . $ar_res['ID'] . '">Добавить материал</button>
                        <button class="btn btn-danger btn-sm delete-services" data-count="' . $count . '"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
                ';
            }
        }
        echo $html;
        break;
    case 'list-products':
        $arSelect = array("*", 'PROPERTY_*');
        $arFilter = array("IBLOCK_ID" => 40, "ACTIVE" => "Y");
        $iblock = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        $html = '';
        while ($ob = $iblock->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arProps = $ob->GetProperties();
            $html .= '
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="products" value="' . $arFields['ID'] . '" id="' . $arFields['ID'] . '">
                        <label class="form-check-label" for="' . $arFields['ID'] . '">' . $arFields['NAME'] . '</label>
                    </div>
                ';
        }
        echo $html;
    case 'print-services':
        $html = '';
        $summ = 0;
        foreach ($_POST['id'] as $item) {
            $db_res = CCatalogProduct::GetList(
                array("QUANTITY" => "DESC"),
                array("ID" => $item),
                false,
                []
            );
            while ($ar_res = $db_res->Fetch()) {
                $price = (int)$ar_res['PURCHASING_PRICE'];
                $html .= '
                    <div class="d-flex align-items-center list-products mt-2" id="' . $ar_res['ID'] . '">
                        <div class="me-3">
                            <span class="form-label" data-summ="' . $price . '" parent="' . $_POST['parent'] . '" data-id="' . $ar_res['ID'] . '">' . $ar_res['ELEMENT_NAME'] . '</span>
                        </div>
                        <button class="btn btn-danger btn-sm" data-summ="' . $price . '" parent="' . $_POST['parent'] . '" data-id="' . $ar_res['ID'] . '" id="delete-products"><i class="fa-solid fa-trash"></i></button>
                    </div>
                ';
                $summ += $ar_res['PURCHASING_PRICE'];
            }
        }
        $result = [
            'html' => $html,
            'summ' => $summ
        ];
        echo json_encode($result);
        break;
    case 'list-material':
        $arSelect = array("*", 'PROPERTY_*');
        $arFilter = array("IBLOCK_ID" => 41, "ACTIVE" => "Y");
        $iblock = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        $html = '';
        while ($ob = $iblock->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arProps = $ob->GetProperties();
            $html .= '
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="material" value="' . $arFields['ID'] . '" id="' . $arFields['ID'] . '">
                        <label class="form-check-label" for="' . $arFields['ID'] . '">' . $arFields['NAME'] . '</label>
                    </div>
                ';
        }
        echo $html;
        break;
    case 'print-materials':
        $html = '';
        $summ = 0;
        foreach ($_POST['id'] as $item) {
            $db_res = CCatalogProduct::GetList(
                array("QUANTITY" => "DESC"),
                array("ID" => $item),
                false,
                []
            );
            while ($ar_res = $db_res->Fetch()) {
                $price = (int)$ar_res['PURCHASING_PRICE'];
                $html .= '
                    <div class="d-flex align-items-center list-materials mt-2" id="' . $ar_res['ID'] . '">
                        <div class="me-3">
                            <span class="form-label" data-summ="' . $price . '" parent="' . $_POST['parent'] . '" data-id="' . $ar_res['ID'] . '">' . $ar_res['ELEMENT_NAME'] . '</span>
                        </div>
                        <button class="btn btn-danger btn-sm" data-summ="' . $price . '" parent="' . $_POST['parent'] . '" data-id="' . $ar_res['ID'] . '" id="delete-material"><i class="fa-solid fa-trash"></i></button>
                    </div>
                ';
                $summ += $ar_res['PURCHASING_PRICE'];
            }
        }
        $result = [
            'html' => $html,
            'summ' => $summ
        ];
        echo json_encode($result);
        break;
    case 'save-product':
        $rows = [];
        foreach ($_POST['arr'] as $item) {
            $rows[] = [
                'PRODUCT_ID' => $item['id'],
                'PRODUCT_NAME' => $item['name'],
                'PRICE' => $item['summ']
            ];
        }
        print_r($rows);
        CCrmProductRow::SaveRows('D', 402, $rows);
        break;
    case 'add-rows':
        $services = servicesProduct();
        $count = $_POST['count'] + 1;
        $html = '
        <div class="services-block" data-rows="' . $count . '">
            <select class="form-select services" id="services">
            <option selected value="">выберите услугу</option>
        ';
        foreach ($services as $item) {
            $html .= '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
        }
        $html .= '</select>
            <div class="products-block"></div>
            <button class="btn btn-danger mt-3 float-end btn-sm me-3" id="delete-rows"><i class="fa-solid fa-trash"></i></i></button>
        </div>
        ';

        echo $html;
        break;
}