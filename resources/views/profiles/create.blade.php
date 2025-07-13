@extends('layouts.app')

@section('content')
    <h2>إضافة بروفايل جديد</h2>

    <form action="{{ route('profiles.store') }}" method="POST">
        @csrf

        <div>
            <label>رقم المستخدم:</label>
            <input type="number" name="user_id" required>
        </div>

        <div>
            <label>رقم الهاتف:</label>
            <input type="text" name="phone">
        </div>

        <div>
            <label>العنوان:</label>
            <input type="text" name="address">
        </div>

        <div>
            <label>تاريخ الميلاد:</label>
            <input type="date" name="date_of_birth">
        </div>

        <div>
            <label>نبذة شخصية:</label>
            <textarea name="bio"></textarea>
        </div>

        <br>
        <button type="submit">حفظ</button>
    </form>
@endsection