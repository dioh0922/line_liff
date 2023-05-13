<?php

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script charset="utf-8" src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <title>Document</title>
</head>
<body class="bg-warning-subtle">
    <div class="container">
        <div class="row mb-e mt-3">
            <h3 class="text-center">使った内容と金額を入力</h3>
        </div>

        <div class="row border border-dark">
            <div class="row ms-1 me-1 mb-3">
                <p class="text-center fs-5 mt-2">内容</p>
                <input type="text" value="" id="pay-detail"/>
            </div>
        </div>

        <div class="row mt-3 border border-dark">
            <div class="row ms-1 mb-3">
                <p class="text-center fs-5 mt-2">金額</p>
                <input type="number" min="0" id="pay-value" value="0"/>
            </div>
        </div>
        <div class="row  mt-5">
            <button onClick="submit()" class="btn btn-primary btn-lg" >
                <span id="btn-content">送信</span>
                <span id="btn-load" class="fade">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="visually-hidden">Loading...</span>
                </span>
            </button>
        </div>
        <div class="row mt-5" >
            <div id="close" class="fade">
                <button class="btn btn-lg btn-secondary" onClick="done()">
                    トークに戻る
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                </div>
                <div class="modal-body">
                    <p>test</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="./credit.js"></script>
</body>
</html>