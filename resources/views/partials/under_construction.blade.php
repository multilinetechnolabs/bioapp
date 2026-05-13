@if (!in_array(Route::getCurrentRoute()->uri(), ['products/bio', 'products/chakra']))
    <div class="row" style="background-color: red; color: #fff; margin: 0;">
        <div class="col-md-12 text-center">
            <span style="font-size: 20px;">This site is still under construction. For inquires, you may contact us through our email, <a href="mailto:anewavenuebio@gmail.com">anewavenuebio@gmail.com</a></span>
        </div>
    </div>
@endif
