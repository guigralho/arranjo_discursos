<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Quadro Discurso Público</title>
        <style>
            html {
                font-family: Arial, sans-serif;
            }

            .content {
                width: 100%;
            }

            h1 {
                font-size: 32px;
                text-align: center;
                color: rgb(111, 168, 220);
            }

            table {
                width: 100%;
                margin-bottom: 25px;
            }

            tr th,
            tr td {
                padding: 15px 20px;
                font-weight: normal;
            }

            thead tr th {
                background: rgb(61, 133, 198);
                color: white;
            }

            thead tr th:first-child {
                width: 100px;
            }

            tbody tr th:first-child {
                background: rgb(207, 226, 243);
                color: black;
            }

            tbody tr:nth-child(2n) th:last-child {
                background: rgb(255, 242, 204);
            }
        </style>
    </head>
    <body>
        <div class="content">
            <h1>Arranjo de Conferência Pública</h1>
            @foreach($to_receive as $receive)
                <table cellspacing="0">
                    <thead>
                        <tr>
                            <th>Congregação</th>
                            <th>{{ data_get($receive, 'is_guest') ? 'Convite' : $congregation }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Data</th>
                            <th>{{ data_get($receive, 'date', '') }}</th>
                        </tr>
                        <tr>
                            <th>Orador</th>
                            <th>{{ data_get($receive, 'speaker', '') }}</th>
                        </tr>
                        <tr>
                            <th>Tema</th>
                            <th>{{ data_get($receive, 'speech.theme', '') }}</th>
                        </tr>
                    </tbody>
                </table>
            @endforeach
        </div>
    </body>
</html>
