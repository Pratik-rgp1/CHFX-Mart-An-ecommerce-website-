// Cart
let cartIcon = document.querySelector('#cart-icon');
let cart = document.querySelector('.cart');
let closeCart = document.querySelector('#close-cart');

// Wishlist
let wishlistIcon = document.querySelector('#wishlist-icon');
let wishlist = document.querySelector('.wishlist');
let closeWishlist = document.querySelector('#close-wishlist');

//Categories Buttons and Search Functionality
const btns = document.querySelectorAll(".button-value");
const storeProducts = document.querySelectorAll(".product-box");


for(i=0; i < btns.length; i++){
    btns[i].addEventListener("click", (e) => {
        e.preventDefault();

        const filter = e.target.dataset.filter;
        console.log(filter)
        storeProducts.forEach((product)=> {
            if(filter == "all"){
                product.style.display = "block";
            } else {
                if(product.classList.contains(filter)
                ){
                    product.style.display = "block";
                    product.style.gridTemplateColumns = "32% 50% 18%";
                    product.style.gap = "10.5rem";
                    product.style.gap = "2rem";
                    product.style.marginTop = "1rem";
                       const pImg = document.querySelector('.product-img');
                    pImg.style.width = "100%";
                } else {
                    product.style.display = "none"
                }
            }
        })
    })
}


// Open Wishlist
wishlistIcon.onclick = () => {
    wishlist.classList.add("active");
}

// Close Wishlist
closeWishlist.onclick = () => {
    wishlist.classList.remove("active");
}


// Open Cart
cartIcon.onclick = () => {
    cart.classList.add("active");
};
// Close Cart
closeCart.onclick = () => {
    cart.classList.remove("active");
};

// Cart working JS
if(document.readyState == "loading"){
    document.addEventListener("DOMContentLoaded", ready);
} else {
    ready();
}

// Making Functions
function ready(){
    // Remove items from cart
    var reomveCartButtons = document.getElementsByClassName('cart-remove')
    console.log(reomveCartButtons)
    for(var i = 0; i < reomveCartButtons.length; i++){
        var button = reomveCartButtons[i];
        button.addEventListener("click", removeCartItem);
    }

    //Remove items from wishlist
    var reomveWishlistButtons = document.getElementsByClassName('wishlist-remove')
    console.log(reomveWishlistButtons)
    for(var i=0; i < reomveWishlistButtons.length; i++){
        var button = reomveWishlistButtons[i];
        button.addEventListener("click", removeWishlistItem);
    }

    //Quantity Changes
    var quantityInputs = document.getElementsByClassName('cart-quantity')
    for(var i = 0; i < quantityInputs.length; i++){
        var input = quantityInputs[i];
        input.addEventListener("change", quantityChanged);
    }

    // Add to Cart
    var addCart = document.getElementsByClassName('add-cart')
    for(var i = 0; i < addCart.length; i++){
        var button = addCart[i];
        button.addEventListener("click", addCartClicked);
    }

    // Add to Wishlist
    var addWishlist = document.getElementsByClassName('add-wishlist')
    for(var i = 0; i < addWishlist.length; i++){
        var button = addWishlist[i];
        button.addEventListener("click", addWishlistClicked);
    }
    
    // Buy Button Work
    document.getElementsByClassName('btn-buy')[0].addEventListener('click', buyButtonClicked);
}

// Buy Button
function buyButtonClicked(){
    alert('Your order has been placed');
    var cartContent = document.getElementsByClassName('cart-content')[0]
    while(cartContent.hasChildNodes()){
        cartContent.removeChildNodes(cartContent.firstChild);
    }
    updatetotal();
}

// Remove items from cart
function removeCartItem(event){
    var buttonClicked = event.target;
    buttonClicked.parentElement.remove();
    updatetotal();
}

// Remove items from wishlist
function removeWishlistItem(event){
    var buttonClicked = event.target;
    buttonClicked.parentElement.remove();
    updatetotal();
}

//Quantity Changes
function quantityChanged(event){
    var input = event.target;
    if(isNaN(input.value) || input.value <= 0) {
        input.value = 1;
    }
    updatetotal();
}

// Add to wishlist
function addWishlistClicked(event){
    var button = event.target;
    var shopProducts = button.parentElement;
    var title = shopProducts.getElementsByClassName("product-title")[0].innerText;
    var price = shopProducts.getElementsByClassName("price")[0].innerText;
    var productImg = shopProducts.getElementsByClassName("product-img")[0].src;
    addProductToWishlist(title, price, productImg);
}

// Add to cart
function addCartClicked(event){
    var button = event.target;
    var shopProducts = button.parentElement;
    var title = shopProducts.getElementsByClassName("product-title")[0].innerText;
    var price = shopProducts.getElementsByClassName("price")[0].innerText;
    var productImg = shopProducts.getElementsByClassName("product-img")[0].src;
    addProductToCart(title, price, productImg);
    updatetotal();
}

function addProductToWishlist(title, price, productImg){
    var wishlistShopBox = document.createElement("div");
    wishlistShopBox.classList.add("wishlist-box");
    var wishlistItems = document.getElementsByClassName('wishlist-content')[0];
    var wishlistItemsNames = wishlistItems.getElementsByClassName('cart-product-title');
    for(var i = 0; i < wishlistItemsNames.length; i++){
        if(wishlistItemsNames[i].innerText == title){
        alert("You have added this item to wishlist");
        return;
        }
    }

    var wishListBoxContent = `
                <img src="${productImg}" alt="" class="cart-img">
                <div class="detail-box">
                <div class="cart-product-title">${title}</div>
                <div class="cart-price">${price}</div>
                </div>
                <!--Remove Wishlist-->
                <i class='bx bxs-trash-alt wishlist-remove' ></i>
          
        `;

    wishlistShopBox.innerHTML = wishListBoxContent
    wishlistItems.append(wishlistShopBox)
    wishlistShopBox.getElementsByClassName('wishlist-remove')[0].addEventListener('click', removeWishlistItem);
}

function addProductToCart(title, price, productImg){
    var cartShopBox = document.createElement("div");
    cartShopBox.classList.add("cart-box");
    var cartItems = document.getElementsByClassName('cart-content')[0];
    var cartItemsNames = cartItems.getElementsByClassName('cart-product-title');
    for(var i = 0; i < cartItemsNames.length; i++){
        if(cartItemsNames[i].innerText == title){
        alert("You have added this item to cart");
        return;
        }
    }

var cartBoxContent = `
        <img src="${productImg}" alt="" class="cart-img">
        <div class="detail-box">
        <div class="cart-product-title">${title}</div>
        <div class="cart-price">${price}</div>
        <input type="number" value="1" class="cart-quantity">
        </div>

        <!--Remove Cart-->
        <i class='bx bxs-trash-alt cart-remove' ></i>`;
cartShopBox.innerHTML = cartBoxContent


cartItems.append(cartShopBox)
cartShopBox.getElementsByClassName('cart-remove')[0].addEventListener('click', removeCartItem);
cartShopBox.getElementsByClassName('cart-quantity')[0].addEventListener('click', quantityChanged);
}

// Update Total
function updatetotal(){
    var cartContent = document.getElementsByClassName('cart-content')[0];
    var cartBoxes = cartContent.getElementsByClassName('cart-box');
    var total = 0;
    for(var i = 0; i < cartBoxes.length; i++){
        var cartBox = cartBoxes[i];
        var priceElement = cartBox.getElementsByClassName('cart-price')[0];
        var quantityElement = cartBox.getElementsByClassName('cart-quantity')[0];
        var price = parseFloat(priceElement.innerText.replace("$", ""));
        var quantity = quantityElement.value;
        total = total + (price * quantity);
    }
        // If price contains cents
        total = Math.round(total * 100) / 100;
        document.getElementsByClassName("total-price")[0].innerText = '$' + total;
}