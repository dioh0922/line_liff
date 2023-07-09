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
    <style>
        .line-break{
            white-space: pre-wrap;
        }
    </style>
</head>
<body class="bg-warning-subtle">
    <div class="container" id="container">
        <v-app>
            <v-app-bar flat color="red-lighten-4" text-align="center" app>
                <h3 class="mx-auto">TODO追加</h3>
            </v-app-bar>
            <v-main>
                <v-container>
                    <v-row justify="center" v-if="!isInit">
                        <v-progress-circular indeterminate :size="79"></v-progress-circular>
                    </v-row>
                    <div v-else>
                        <v-row>
                            <h3 class="mx-auto">概要</h3>
                        </v-row>
                        <v-row>
                            <v-col cols="10" class="mx-auto">
                                <v-text-field class="mx-auto" v-model:model-value="title" width="60%" v-model="title"></v-text-field>
                            </v-col>
                        </v-row>

                        <v-row>
                            <h3 class="mx-auto">詳細</h3>
                        </v-row>

                        <v-row>
                            <v-col cols="10" class="mx-auto">
                                <v-textarea class="mx-auto" v-model:model-value="todo" width="60%" maxLength="300"></v-textarea>
                            </v-col>
                        </v-row>

                        <v-row v-show="!complete">
                            <v-btn class="mx-auto" width="80%" color="blue" @click="submit" :loading="loading">送信</v-btn>
                        </v-row>

                        <v-row v-show="complete">
                            <v-btn class="mx-auto mt-10" width="80%" color="grey" @click="done">トークに戻る</v-btn>
                        </v-row>

                        <v-row class="mt-5">
                            <v-col cols="10" class="mx-auto">
                                <v-expansion-panels color="mt-1" v-if="isInit">
                                    <v-expansion-panel>
                                        <v-expansion-panel-title color="green-lighten-4">
                                            開発TODO
                                        </v-expansion-panel-title>

                                        <v-expansion-panel-text  v-for="(item, idx) in lists">
                                            <v-btn color="warning" width="100%" :loading="loading" @click="showTodo(item)">{{item.title}}</v-btn>                                            
                                        </v-expansion-panel-text>
                                    </v-expansion-panel>
                                </v-expansion-panels>
                            </v-col>
                        </v-row>
                    </div>
                </v-container>
            </v-main>
            <v-dialog v-model="taskDialog.disp">
                <v-card>
                    <v-card-text class="mx-auto line-break">
                        <h4>{{taskDialog.title}}</h4>
                        <p>{{taskDialog.detail}}</p>
                        <v-btn color="green-lighten-1" @click="taskDone(taskDialog)" :loading="loading">完了</v-btn>
                    </v-card-text>
                    <v-btn @click="closeTodo" class="mx-auto mb-5" width="50%" color="grey">閉じる</v-btn>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog.disp">
                <v-card>
                    <v-card-text class="mx-auto line-break">
                        <h4>{{dialog.msg}}</h4>
                    </v-card-text>
                    <v-btn @click="closeDialog" class="mx-auto mb-5" width="50%" color="grey">閉じる</v-btn>
                </v-card>
                
            </v-dialog>
        </v-app>
    </div>
    
    <script src="./todo.js"></script>
</body>
</html>