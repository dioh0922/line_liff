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

    <link href="https://unpkg.com/vuetify@3.3.4/dist/vuetify.min.css" rel="stylesheet">
    <script src="https://unpkg.com/vuetify@3.3.4/dist/vuetify.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <title>旅行メモ</title>
</head>
<body class="bg-warning-subtle">
    <div class="container" id="container">
    <v-app>
        <v-main>
            <v-container>
                <v-app-bar  color="yellow-lighten-4">
                    <v-col>
                        <p class="text-center" justify="center">旅行メモ</p>
                    </v-col>
                </v-app-bar>
                <v-row justify="center">
                    <v-text-field v-model="text" placeholder="目的地を入力"></v-text-field>
                </v-row>

                <v-row justify="center">
                    <v-btn :block=true :loading="loading" color="info" @click="submit">追加</v-btn>
                </v-row>

                <v-row class="mt-10"  justify="space-around">
                    <v-expansion-panels color="green-lighten-4" v-if="isInit">
                        <v-expansion-panel title="TODO">
                            <v-expansion-panel-text  v-for="(item, idx) in lists" border>
                                <v-col class="d-flex">
                                    <v-sheet class="my-auto">
                                        {{item.destination}}
                                    </v-sheet>
                                    <v-sheet  class="my-auto ml-auto">
                                        <v-btn  color="red-lighten-5"  @click="submitDone(item.destination)">完了</v-btn>
                                    </v-sheet>
                                </v-col>
                            </v-expansion-panel-text>
                        </v-expansion-panel>
                    </v-expansion-panels>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
    </div>
    <script src="./travel.js"></script>
</body>
</html>