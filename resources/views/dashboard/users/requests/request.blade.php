@extends('layout.dash')
@section('title')
    NeedLink - طلباتي
@endsection
@section('sub-title')
    طلباتي
@endsection
@section('message')
    مرحبا في طلباتي تابع اخر الطلبات الخاصة بك
@endsection
@section('content')
    <aside class="panel" style="padding:28px; margin-top: 30px;">
        <h2>معاينة فورية</h2>
        <div class="feed-card">
            <span class="badge badge-orange">عاجل</span>
            <h3>محتاج مصمم واجهات اليوم</h3>
            <p class="muted">إعادة تصميم شاشتين لتطبيق طلابي بشكل سريع.</p>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <span class="badge badge-blue">تصميم</span>
                <span class="badge badge-orange">ميزانية $15</span>
                <span class="badge badge-blue">اليوم</span>
            </div>
        </div>
    </aside>

@endsection