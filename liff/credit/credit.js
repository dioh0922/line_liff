
function submit(){

    let pay = parseInt(document.getElementById("pay-value").value);
    if(parseInt(pay) <= 0){
        return ;
    }

    document.getElementById("btn-load").className = "";
    document.getElementById("btn-content").innerHTML = "Loading...";

    let post_data = new FormData();
    post_data.append("value", pay);
    axios.post("./save.php", post_data).then(res => {

    }).catch(er => {

    }).finally(() => {
        document.getElementById("btn-load").className = "fade";
        document.getElementById("btn-content").innerHTML = "送信";
    });
}
