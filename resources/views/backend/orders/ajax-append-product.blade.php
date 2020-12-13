
<tr class="parent-orders">

    <td>

        <div class="product-box">

            <div class="product-image">

                <a href="#">

                    <img src="{{url('/')}}/public/backend/img/placeholder.png" style="max-width: 100px; max-height: 100px; width: 100%; height: 100%;">

                </a>

            </div>

            <div class="product-content">

                <h4 class="product-name">

                    <a href="#" class="product-link">Chọn sản phẩm</a>

                </h4>

            </div>  

        </div>

    </td>

    <td>

        <div class="product-prices">

            <span class="price">{{number_format(0, 0, '.', '.')}}vnđ</span>

        </div>

    </td>

    <td>
        <div class="qty">

            <button class="btn icon-minus icon-minus-pre"><i class="far fa-minus icon"></i></button>

            <input class="product-qty" type="number" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" min="1" name="product_qty" value="1">

            <button class="btn icon-minus icon-minus-next"><i class="far fa-plus icon"></i></button>

        </div>

    </td>

    <td>

        <div class="product-prices">

            <span class="price-total cartitem-price">{{number_format(0, 0, '.', '.')}}vnđ</span>

        </div>

    </td>

    <td>    

        <a href="" class="delete delete-product">

            <i class="far fa-trash-alt icon"></i>

            <span>Xóa</span>

        </a>

    </td>

</tr>