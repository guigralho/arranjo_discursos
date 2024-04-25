<table>
    <tbody>
    <tr>
        <td style="text-align: center; font-size: 14px; font-weight: bold">Oradores - Congregação Vila Medeiros</td>
    </tr>
    @foreach($speakers as $speaker)
        <tr>
            <td style="font-weight: bold">{{ $speaker->name }}</td>
        </tr>
        <tr>
            <td>Temas</td>
        </tr>
        @foreach($speaker->speeches as $speech)
            <tr>
                <td>{{ $speech->number }} - {{ $speech->theme }}</td>
            </tr>
        @endforeach
        <tr>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>
