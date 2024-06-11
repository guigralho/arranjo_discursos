<table style="font-size: 11pt; font-family: Arial;">
    <tr style="">
        <td colspan="6" style="font-weight: bold; text-align: center;height: 50px; font-size: 15pt;">PLANILHA DE ASSENTO
            NO ÔNIBUS
        </td>
    </tr>
    <tr>
        <td colspan="6" style="background-color: #44546a; color: #ffffff; text-align: center;font-size: 9pt;">Este
            formulário deve ser preenchido e enviado ao coordenador do circuito
        </td>
    </tr>
    <tr style="">
        <td colspan="2" style="font-size: 13pt;height: 30px;text-align: right">
            Congregação
        </td>
        <td style="font-size: 13pt;text-decoration: underline;">
            Vila Medeiros
        </td>
        <td colspan="2" style="font-size: 13pt;text-align: right;">
            Circuito
        </td>
        <td style="font-size: 13pt;text-decoration: underline;">
            SP-28
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 13pt;height:30px;text-align: right;">
            Cidade
        </td>
        <td style="font-size: 13pt;text-decoration: underline;">
            São Paulo
        </td>
        <td colspan="2" style="font-size: 13pt;text-align: right;">
            Estado
        </td>
        <td style="font-size: 13pt;text-decoration: underline;">
            SP
        </td>
    </tr>
    <tr style="">
        <td colspan="6"
            style="background-color: #4472c4; color: #ffffff; text-align: center;font-size: 12pt; font-weight: bold; height: 30px;">
            RELAÇÃO DE PASSAGEIROS
        </td>
    </tr>

    <tr>
        <td style="width: 4%;height: 25px"></td>
        <td style="text-align: center;background-color: #b4c7e7;font-weight: bold;width: 23%;">Nome</td>
        <td style="text-align: center;background-color: #b4c7e7;font-weight: bold;width: 23%;">RG</td>
        <td style="width: 4%;"></td>
        <td style="text-align: center;background-color: #b4c7e7;font-weight: bold;width: 23%;">Nome</td>
        <td style="text-align: center;background-color: #b4c7e7;font-weight: bold;width: 23%;">RG</td>
    </tr>

    @for ($i = 0; $i < 23; $i++)
        <tr>
            <td style="text-align: center;background-color: #4472c4;font-weight: bold;width: 4%;color: #ffffff;height: 30px;">{{ $i+1 }}</td>
            <td style="width: 23%;text-align: center;">{{ data_get($passengers, $i.'.passenger.name', '') }}</td>
            <td style="width: 23%;text-align: center;">{{ data_get($passengers, $i.'.passenger.doc', '') }}</td>
            <td style="text-align: center;background-color: #4472c4;font-weight: bold;width: 4%;color: #ffffff;">{{ $i+23 }}</td>
            <td style="width: 23%;text-align: center;">{{ data_get($passengers, ($i+23).'.passenger.name', '') }}</td>
            <td style="width: 23%;text-align: center;">{{ data_get($passengers, ($i+23).'.passenger.doc', '') }}</td>
        </tr>
    @endfor

    <tr>
        <td colspan="6"></td>
    </tr>

    <tr style="">
        <td></td>
        <td style="font-size: 13pt;height: 40px;text-align: right">
            Encarregado
        </td>
        <td style="font-size: 13pt;height: 40px;">
            ______________
        </td>
    </tr>

    <tr style="">
        <td></td>
        <td style="font-size: 13pt;height: 40px;text-align: right">
            Data
        </td>
        <td style="font-size: 13pt;height: 40px;">
            {{ date('d/m/Y') }}
        </td>
    </tr>
</table>
