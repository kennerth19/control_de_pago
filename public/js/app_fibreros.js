function modal(id, count) {
    let modal = document.querySelector(".modal");
    let modal_item = [];

    modal.style.display = "block";

    for (i = 1; i <=count; i++) {
        modal_item[i] = document.getElementById("modal_" + i)

        if (modal_item[i].id != "modal_" + id) {
            modal_item[i].style.display = "none";
        } else {
            modal_item[i].style.display = "block";
        }
    }
}

function modal_f() {
    let modal_f = document.querySelector(".modal");

    modal_f.style.display = "none";
}

function modal_r(id, count) {
    let modal_r = document.querySelector(".modal_r");
    let modal_item_r = [];

    modal_r.style.display = "block";

    for (i = 1; i <= count; i++) {
        modal_item_r[i] = document.getElementById("modal_r_" + i)

        if (modal_item_r[i].id != "modal_r_" + id) {
            modal_item_r[i].style.display = "none";
        } else {
            modal_item_r[i].style.display = "block";
        }
    }    
}

function modal_r_f() {
    let modal_r_f = document.querySelector(".modal_r");

    modal_r_f.style.display = "none";
}