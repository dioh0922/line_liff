<?php

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script charset="utf-8" src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>

    <script src="https://unpkg.com/vue@3.3.2/dist/vue.global.js"></script>

    <link href="https://unpkg.com/vuetify@3.3.2/dist/vuetify.min.css" rel="stylesheet">
    <script src="https://unpkg.com/vuetify@3.3.2/dist/vuetify.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <title>Document</title>
</head>
<body class="bg-warning-subtle">
    <div class="container" id="container">
        <v-app>
            <v-app-bar flat color="green" text-align="center" app>
                <h3 class="mx-auto">使った内容と金額を入力</h3>
            </v-app-bar>
            <v-main>
                <v-container>
                    <v-row>
                        <h3 class="mx-auto">内容</h3>
                    </v-row>
                    <v-row>
                        <v-col cols="10" class="mx-auto">
                            <v-text-field class="mx-auto" width="60%" v-model="detail"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <h3 class="mx-auto">金額</h3>
                    </v-row>
                    <v-row>
                        <v-col cols="10" class="mx-auto">
                            <v-text-field class="mx-auto" type="number" min="1" width="60%" v-model="pay"></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row v-show="!done">
                        <v-btn class="mx-auto" width="80%" color="blue" @click="submit" :loading="loading">送信</v-btn>
                    </v-row>

                    <v-row v-show="done">
                        <v-btn class="mx-auto mt-10" width="80%" color="grey" @click="submit">トークに戻る</v-btn>
                    </v-row>

                </v-container>
            </v-main>
            <v-dialog v-model="dialog.disp">
                <v-card>
                    <v-card-text class="mx-auto">
                        <h4>{{dialog.msg}}</h4>
                    </v-card-text>
                    <v-btn @click="closeDialog" class="mx-auto mb-5" width="50%" color="grey">閉じる</v-btn>
                </v-card>
                
            </v-dialog>
        </v-app>
    </div>    
    <script src="./credit.js"></script>
</body>
</html>