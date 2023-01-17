<table>
    <tbody>
    @foreach($speakers as $speaker)
        <tr>
            <td>Orador</td>
        </tr>
        <tr>
            <td>{{ $speaker->name }}</td>
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
