const price_range = document.querySelectorAll(".range-input input"),
      data_price = document.querySelectorAll(".price-input input"),
      range = document.querySelector(".slider .progress");

let priceGap = 1;

function get_url(name) {
    const url = new URLSearchParams(window.location.search);
    return url.get(name);
}
const getmin = get_url('min_price') || 0; 
const getmax = get_url('max_price') || 100000; 

data_price[0].value = getmin;
data_price[1].value = getmax;

price_range[0].value = getmin;
price_range[1].value = getmax;

range.style.left = ((getmin / price_range[0].max) * 100) + "%";
range.style.right = 100 - (getmax / price_range[1].max) * 100 + "%";

data_price.forEach(input => {
    input.addEventListener("input", e => {
        let minPrice = parseInt(data_price[0].value),
            maxPrice = parseInt(data_price[1].value);
        
        if ((maxPrice - minPrice >= priceGap) && maxPrice <= price_range[1].max) {
            if (e.target.className === "input-min") {
                price_range[0].value = minPrice;
                range.style.left = ((minPrice / price_range[0].max) * 100) + "%";
            } else {
                price_range[1].value = maxPrice;
                range.style.right = 100 - (maxPrice / price_range[1].max) * 100 + "%";
            }
        }
    });
});

price_range.forEach(input => {
    input.addEventListener("input", e => {
        let minVal = parseInt(price_range[0].value),
            maxVal = parseInt(price_range[1].value);

        if ((maxVal - minVal) < priceGap) {
            if (e.target.className === "range-min") {
                price_range[0].value = maxVal - priceGap;
            } else {
                price_range[1].value = minVal + priceGap;
            }
        } else {
            data_price[0].value = minVal;
            data_price[1].value = maxVal;
            range.style.left = ((minVal / price_range[0].max) * 100) + "%";
            range.style.right = 100 - (maxVal / price_range[1].max) * 100 + "%";
        }
    });
});

// function setupcheckbox(checkboxname) {
//     const checkAll = document.querySelector(".checkAll"+checkboxname);
//     const checkItems = document.querySelectorAll("."+checkboxname);
//     checkAll.checked = true;
//     checkItems.forEach(item => item.checked = checkAll.checked);
    
//     checkAll.addEventListener("change", function () {
//         checkItems.forEach(item => item.checked = checkAll.checked);
//     });

//     checkItems.forEach(item => {
//         item.addEventListener("change", function () {
//             checkAll.checked = [...checkItems].every(i => i.checked);
//         });
//     });
// }
// setupcheckbox('building-type-checkbox');