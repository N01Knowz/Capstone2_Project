@extends('layouts.student_navigation')
@section('title', 'Analytics')

@push('styles')
<link rel="stylesheet" href="/css/front_page.css">
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/analyticsIndex.css">
@endpush
@section('modal-contents')
@endsection

@section('content')
<div class="body-content">
    <div class="riasec-bargraph">
        <table class="table-bargraph">
            <tbody>
                <tr>
                    <td>Realistic: </td>
                    <td>
                        <div style="background-color: #473C1F; width: {{ ($tags['Realistic'][1] != 0) ? number_format($tags['Realistic'][0] / $tags['Realistic'][1] * 100, 2) . '%' : '0%' }};">{{ ($tags['Realistic'][1] != 0) ? number_format($tags['Realistic'][0] / $tags['Realistic'][1] * 100, 2) . '%' : '0%' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>Investigative: </td>
                    <td>
                        <div style="background-color: #EEEBD3; width: {{ ($tags['Investigative'][1] != 0) ? number_format($tags['Investigative'][0] / $tags['Investigative'][1] * 100, 2) . '%' : '0%' }};">{{ ($tags['Investigative'][1] != 0) ? number_format($tags['Investigative'][0] / $tags['Investigative'][1] * 100, 2) . '%' : '0%' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>Artistic: </td>
                    <td>
                        <div style="background-color: #A98743; width: {{ ($tags['Artistic'][1] != 0) ? number_format($tags['Artistic'][0] / $tags['Artistic'][1] * 100, 2) . '%' : '0%' }};">{{ ($tags['Artistic'][1] != 0) ? number_format($tags['Artistic'][0] / $tags['Artistic'][1] * 100, 2) . '%' : '0%' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>Social: </td>
                    <td>
                        <div style="background-color: #F7C548; width: {{ ($tags['Social'][1] != 0) ? number_format($tags['Social'][0] / $tags['Social'][1] * 100, 2) . '%' : '0%' }};">{{ ($tags['Social'][1] != 0) ? number_format($tags['Social'][0] / $tags['Social'][1] * 100, 2) . '%' : '0%' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>Enterprising: </td>
                    <td>
                        <div style="background-color: #255957; width: {{ ($tags['Enterprising'][1] != 0) ? number_format($tags['Enterprising'][0] / $tags['Enterprising'][1] * 100, 2) . '%' : '0%' }};">{{ ($tags['Enterprising'][1] != 0) ? number_format($tags['Enterprising'][0] / $tags['Enterprising'][1] * 100, 2) . '%' : '0%' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>Conventional: </td>
                    <td>
                        <div style="background-color: #437C90; width: {{ ($tags['Conventional'][1] != 0) ? number_format($tags['Conventional'][0] / $tags['Conventional'][1] * 100, 2) . '%' : '0%' }};">{{ ($tags['Conventional'][1] != 0) ? number_format($tags['Conventional'][0] / $tags['Conventional'][1] * 100, 2) . '%' : '0%' }}</div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

@endsection

@if(session('success'))
<script>
    var message = "{{ session('success') }}";
    alert(message);
</script>
@endif