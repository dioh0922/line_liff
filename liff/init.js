(window.onload = () => {
  axios.get("../../../util_api/liffId.php").then((res) => {
    liff.init({liffId: res.data.liffId}).then(() => {

      document.getElementById("detail").innerHTML = liff.state + "<br>";
    });
  });
});