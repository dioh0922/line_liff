
function submit(){

    let pay = parseInt(document.getElementById("pay-value").value);
    let detail = document.getElementById("pay-detail").value;
    if(parseInt(pay) <= 0 || detail === ""){
        return ;
    }

    document.getElementById("btn-load").className = "";
    document.getElementById("btn-content").innerHTML = "Loading...";

    let post_data = new FormData();
    post_data.append("value", pay);
    post_data.append("detail", detail);
    axios.post("./save.php", post_data).then(res => {
        document.getElementById("close").className = "row ms-1 mb-3";
    }).catch(er => {

    }).finally(() => {
        document.getElementById("btn-load").className = "fade";
        document.getElementById("btn-content").innerHTML = "送信";
    });
}

function done(){
    liff.closeWindow();
}