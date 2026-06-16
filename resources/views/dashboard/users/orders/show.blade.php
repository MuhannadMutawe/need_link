@extends('layout.dash')

@section('title', 'تفاصيل الطلب | NeedLink')

@section('content')
<div class="container-fluid p-4">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('dashboard.orders.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-right"></i> عودة لطلباتي</a>
            <h4 class="mb-0 fw-bold d-flex align-items-center gap-2">
                {{ $order->serviceRequest->title }}
                <span class="badge bg-light text-dark border fs-6">{{ $order->order_type === 'service' ? 'خدمة' : 'منتج' }}</span>
            </h4>
        </div>
        
        @php
            $statusColor = 'secondary';
            $statusText = 'قيد الانتظار';
            
            switch($order->status) {
                case 'in_progress': $statusColor = 'primary'; $statusText = 'قيد التنفيذ'; break;
                case 'completed_pending_confirmation': $statusColor = 'warning text-dark'; $statusText = 'بانتظار التأكيد'; break;
                case 'completed': $statusColor = 'success'; $statusText = 'مكتمل'; break;
                case 'cancelled': $statusColor = 'secondary'; $statusText = 'ملغى'; break;
                case 'disputed': $statusColor = 'danger'; $statusText = 'متنازع عليه'; break;
            }
            
            $isClient = $user->id === $order->client_id;
            $otherParty = $isClient ? $order->provider : $order->client;
        @endphp
        <span class="badge bg-{{ $statusColor }} fs-5 py-2 px-3">{{ $statusText }}</span>
    </div>

    <!-- Active Dispute Banner -->
    @if($order->status === 'disputed')
        @php
            $activeDispute = $order->disputes()->where('status', 'open')->first();
        @endphp
        <div class="alert alert-danger mb-4 shadow-sm border-danger">
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-exclamation-triangle-fill fs-3 mt-1"></i>
                <div class="w-100">
                    <h5 class="alert-heading fw-bold">الطلب متنازع عليه</h5>
                    <p class="mb-2">قيد المراجعة من قبل الإدارة. تم تعطيل كافة الإجراءات حتى يتم حل النزاع.</p>
                    
                    @if($activeDispute)
                        <div class="bg-white bg-opacity-50 p-3 rounded mt-3">
                            <h6 class="fw-bold mb-1">سبب النزاع (من {{ $activeDispute->opened_by === $user->id ? 'قبلك' : 'الطرف الآخر' }}):</h6>
                            <p class="mb-0 small text-dark">{{ $activeDispute->reason }}</p>
                        </div>
                        
                        @if($activeDispute->counter_reason)
                            <div class="bg-white bg-opacity-50 p-3 rounded mt-2">
                                <h6 class="fw-bold mb-1">رد {{ $activeDispute->opened_by === $user->id ? 'الطرف الآخر' : 'قبلك' }} على النزاع:</h6>
                                <p class="mb-0 small text-dark">{{ $activeDispute->counter_reason }}</p>
                            </div>
                        @elseif($activeDispute->opened_by !== $user->id)
                            <hr class="border-danger opacity-25 my-3">
                            <p class="fw-bold mb-2">يرجى إضافة ردك وتوضيح موقفك للإدارة (مهم جداً):</p>
                            <form action="{{ route('dashboard.orders.actions.dispute.respond', $order->id) }}" method="POST">
                                @csrf
                                <div class="mb-2">
                                    <textarea name="counter_reason" class="form-control" rows="3" required placeholder="اشرح وجهة نظرك بالتفصيل للإدارة..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger btn-sm fw-bold px-4">إرسال الرد للإدارة</button>
                            </form>
                        @else
                            <div class="alert alert-warning mt-3 mb-0 small py-2">
                                <i class="bi bi-hourglass-split me-1"></i> في انتظار رد الطرف الآخر على النزاع.
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Pending Cancellation Banner -->
    @php
        $pendingCancellation = $order->cancellationRequests()->where('status', 'pending')->first();
    @endphp
    @if($pendingCancellation && $order->status !== 'disputed')
        <div class="alert alert-warning mb-4 shadow-sm border-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-exclamation-circle-fill fs-3 text-warning"></i>
                    <div>
                        <h6 class="alert-heading fw-bold mb-1">
                            {{ $pendingCancellation->requested_by === $user->id ? 'لقد طلبت إلغاء هذا الطلب' : 'الطرف الآخر يطلب إلغاء الطلب' }}
                        </h6>
                        <p class="mb-0 text-dark small">السبب: {{ $pendingCancellation->reason }}</p>
                    </div>
                </div>
                
                @if($pendingCancellation->requested_by !== $user->id)
                    <div class="d-flex gap-2">
                        <form action="{{ route('dashboard.orders.actions.cancellation.respond', [$order->id, $pendingCancellation->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="accept">
                            <button type="submit" class="btn btn-danger btn-sm rounded-pill fw-bold" onclick="return confirm('هل أنت متأكد من قبول الإلغاء؟ سيتم إلغاء الطلب فوراً.')">الموافقة على الإلغاء</button>
                        </form>
                        <form action="{{ route('dashboard.orders.actions.cancellation.respond', [$order->id, $pendingCancellation->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold">رفض الطلب</button>
                        </form>
                    </div>
                @else
                    <span class="badge bg-warning text-dark">في انتظار رد الطرف الآخر</span>
                @endif
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            
            <!-- Details Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-1">{{ $isClient ? 'مقدم الخدمة' : 'العميل' }}</small>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 16px;">
                                    {{ mb_substr($otherParty->name, 0, 1) }}
                                </div>
                                <span class="fw-bold fs-5">{{ $otherParty->name }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-1">السعر المتفق عليه</small>
                            <span class="fw-bold fs-5 text-success">{{ $order->agreed_price }} {{ $order->currency_code }}</span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-1">تاريخ البدء</small>
                            <span class="fw-bold"><i class="bi bi-calendar3 me-1"></i> {{ $order->started_at ? $order->started_at->format('Y-m-d H:i') : 'غير متوفر' }}</span>
                        </div>
                        @if($order->completed_at)
                            <div class="col-sm-6">
                                <small class="text-muted d-block mb-1">تاريخ الاكتمال</small>
                                <span class="fw-bold"><i class="bi bi-calendar3-check me-1"></i> {{ $order->completed_at->format('Y-m-d H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Panel -->
            @if($order->status !== 'disputed' && $order->status !== 'cancelled' && $order->status !== 'completed' && !$pendingCancellation)
                <div class="card border-0 shadow-sm mb-4 border-top border-primary border-3">
                    <div class="card-header bg-white border-0 pt-4 pb-0">
                        <h5 class="fw-bold mb-0">الإجراءات المتاحة</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <!-- Client Actions -->
                        @if($isClient)
                            
                            @if($order->order_type === 'service' && $order->status === 'completed_pending_confirmation')
                                <div class="alert alert-info">لقد قام مقدم الخدمة بتسليم العمل. يرجى المراجعة والتأكيد.</div>
                                <div class="d-flex gap-3">
                                    <form action="{{ route('dashboard.orders.actions.confirmCompletion', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success fw-bold px-4"><i class="bi bi-check-circle me-1"></i> تأكيد الاستلام واكتمال الطلب</button>
                                    </form>
                                    
                                    @if($order->revision_count < 3)
                                        <button type="button" class="btn btn-outline-warning fw-bold" data-bs-toggle="modal" data-bs-target="#revisionModal">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> طلب تعديل
                                        </button>
                                    @else
                                        <button class="btn btn-outline-secondary" disabled title="تم استنفاد عدد مرات التعديل المسموح بها"><i class="bi bi-arrow-counterclockwise me-1"></i> طلب تعديل</button>
                                    @endif
                                </div>
                            @endif

                            @if($order->order_type === 'product')
                                @if($order->status === 'in_progress' && !$order->is_paid)
                                    <div class="alert alert-info">يرجى تحويل مبلغ الطلب خارج المنصة وتأكيد ذلك ليتمكن البائع من شحن المنتج.</div>
                                    <form action="{{ route('dashboard.orders.actions.confirmPayment', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-credit-card me-1"></i> تأكيد إرسال المبلغ</button>
                                    </form>
                                @elseif($order->status === 'completed_pending_confirmation' && $order->is_shipped)
                                    <div class="alert alert-info">تم شحن المنتج. يرجى تأكيد الاستلام عند وصوله إليك.</div>
                                    <form action="{{ route('dashboard.orders.actions.confirmReceipt', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success fw-bold"><i class="bi bi-box-seam me-1"></i> تأكيد استلام المنتج</button>
                                    </form>
                                @endif
                            @endif

                        <!-- Provider Actions -->
                        @else
                            
                            @if($order->order_type === 'service' && $order->status === 'in_progress')
                                <button type="button" class="btn btn-primary fw-bold px-4" data-bs-toggle="modal" data-bs-target="#deliveryModal">
                                    <i class="bi bi-cloud-arrow-up me-1"></i> تسليم العمل
                                </button>
                            @endif

                            @if($order->order_type === 'product')
                                @if($order->status === 'in_progress')
                                    @if(!$order->is_paid)
                                        <div class="alert alert-warning mb-0">في انتظار تأكيد العميل للدفع خارج المنصة.</div>
                                    @elseif(!$order->is_shipped)
                                        <div class="alert alert-success">قام العميل بتأكيد الدفع. يرجى شحن المنتج وإضافة معلومات التتبع.</div>
                                        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#trackingModal">
                                            <i class="bi bi-truck me-1"></i> إضافة معلومات الشحن
                                        </button>
                                    @endif
                                @endif
                            @endif

                        @endif

                        <hr class="my-4">
                        
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="bi bi-x-circle me-1"></i> طلب إلغاء الطلب
                            </button>
                            
                            @php
                                $canDispute = false;
                                if ($order->order_type === 'service' && $order->deliveries()->count() > 0) $canDispute = true;
                                if ($order->order_type === 'product' && $order->is_paid) $canDispute = true;
                            @endphp
                            
                            @if($canDispute)
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#disputeModal">
                                    <i class="bi bi-exclamation-triangle me-1"></i> رفع نزاع للإدارة
                                </button>
                            @endif
                        </div>
                        
                    </div>
                </div>
            @endif

            <!-- Activity Feed -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom pt-4 pb-3">
                    <h5 class="fw-bold mb-0">سجل النشاطات</h5>
                </div>
                <div class="card-body p-4">
                    
                    @php
                        // Collect all events to sort them chronologically
                        $events = collect([]);
                        
                        // Order created
                        $events->push([
                            'type' => 'created',
                            'date' => $order->started_at,
                            'title' => 'تم بدء الطلب',
                            'desc' => "السعر: {$order->agreed_price} {$order->currency_code}",
                            'icon' => 'bi-play-circle-fill text-primary'
                        ]);
                        
                        // Deliveries
                        foreach($order->deliveries as $delivery) {
                            $events->push([
                                'type' => 'delivery',
                                'date' => $delivery->created_at,
                                'title' => 'تم تسليم العمل',
                                'desc' => $delivery->message,
                                'attachments' => $delivery->attachments,
                                'user' => $delivery->submitter->name,
                                'icon' => 'bi-cloud-check-fill text-success'
                            ]);
                        }
                        
                        // Revisions
                        foreach($order->revisions as $rev) {
                            $events->push([
                                'type' => 'revision',
                                'date' => $rev->created_at,
                                'title' => 'طلب تعديل',
                                'desc' => $rev->reason,
                                'user' => $rev->requester->name,
                                'icon' => 'bi-arrow-counterclockwise text-warning'
                            ]);
                        }
                        
                        // Sort by date DESC
                        $events = $events->sortByDesc('date')->values();
                    @endphp

                    <div class="position-relative ms-3">
                        <!-- Timeline line -->
                        <div class="position-absolute h-100 border-start border-2 border-light" style="left: 11px; top: 0; z-index: 1;"></div>
                        
                        @foreach($events as $event)
                            <div class="position-relative mb-4 pb-2" style="z-index: 2;">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="bg-white rounded-circle mt-1" style="width: 24px; height: 24px;">
                                        <i class="bi {{ $event['icon'] }} fs-4 bg-white"></i>
                                    </div>
                                    <div class="flex-grow-1 bg-light rounded p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0">{{ $event['title'] }}</h6>
                                            <small class="text-muted" dir="ltr">{{ $event['date'] ? $event['date']->format('Y-m-d H:i') : '' }}</small>
                                        </div>
                                        @if(isset($event['user']))
                                            <small class="text-muted d-block mb-2">بواسطة: {{ $event['user'] }}</small>
                                        @endif
                                        <p class="mb-0 small text-dark">{{ $event['desc'] }}</p>
                                        
                                        @if(isset($event['attachments']) && $event['attachments']->count() > 0)
                                            <div class="mt-3 d-flex flex-wrap gap-2">
                                                @foreach($event['attachments'] as $att)
                                                    <a href="{{ Storage::url($att->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary" style="font-size: 12px;">
                                                        <i class="bi bi-paperclip"></i> {{ $att->file_name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
            
        </div>
        
        <!-- Sidebar Column -->
        <div class="col-lg-4">
            @if(!in_array($order->status, ['cancelled', 'disputed']))
            <!-- Order status overview -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">حالة التنفيذ</h6>
                    <ul class="list-group list-group-flush list-group-timeline">
                        <li class="list-group-item px-0 border-0 d-flex gap-3 align-items-center text-success">
                            <i class="bi bi-check-circle-fill"></i>
                            <div>تم بدء التنفيذ</div>
                        </li>
                        
                        @if($order->order_type === 'product')
                            <li class="list-group-item px-0 border-0 d-flex gap-3 align-items-center {{ $order->is_paid ? 'text-success' : 'text-muted' }}">
                                <i class="bi {{ $order->is_paid ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                                <div>تأكيد الدفع</div>
                            </li>
                            <li class="list-group-item px-0 border-0 d-flex gap-3 align-items-center {{ $order->is_shipped ? 'text-success' : 'text-muted' }}">
                                <i class="bi {{ $order->is_shipped ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                                <div>تم الشحن</div>
                            </li>
                        @else
                            <li class="list-group-item px-0 border-0 d-flex gap-3 align-items-center {{ $order->status === 'completed_pending_confirmation' || $order->status === 'completed' ? 'text-success' : 'text-muted' }}">
                                <i class="bi {{ $order->status === 'completed_pending_confirmation' || $order->status === 'completed' ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                                <div>تم التسليم المبدئي</div>
                            </li>
                        @endif
                        
                        <li class="list-group-item px-0 border-0 d-flex gap-3 align-items-center {{ $order->status === 'completed' ? 'text-success' : 'text-muted' }}">
                            <i class="bi {{ $order->status === 'completed' ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                            <div>مكتمل ومؤكد</div>
                        </li>
                    </ul>

                    @if($order->confirm_deadline_at && $order->status === 'completed_pending_confirmation')
                        <div class="alert alert-info mt-3 mb-0 small py-2">
                            <i class="bi bi-info-circle me-1"></i> سيتم تأكيد الطلب تلقائياً بعد {{ $order->confirm_deadline_at->diffForHumans() }}
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Tracking Details (if product) -->
            @if($order->order_type === 'product' && $order->is_shipped)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">معلومات الشحن</h6>
                        <div class="mb-2">
                            <small class="text-muted d-block">شركة الشحن</small>
                            <span class="fw-bold">{{ $order->carrier ?? 'غير محدد' }}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">رقم التتبع</small>
                            <span class="fw-bold font-monospace">{{ $order->tracking_number ?? 'غير محدد' }}</span>
                        </div>
                        @if($order->tracking_url)
                            <div class="mt-3">
                                <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">تتبع الشحنة <i class="bi bi-box-arrow-up-right ms-1"></i></a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<!-- Modals -->

<!-- Delivery Modal -->
<div class="modal fade" id="deliveryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">تسليم العمل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.orders.actions.delivery', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">رسالة للعميل</label>
                        <textarea name="message" class="form-control" rows="4" required placeholder="اشرح ما قمت بإنجازه..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">المرفقات</label>
                        <input type="file" name="attachments[]" class="form-control" multiple>
                        <div class="form-text">يمكنك إرفاق عدة ملفات (الحد الأقصى 10 ميجابايت للملف).</div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">إرسال التسليم</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Revision Modal -->
<div class="modal fade" id="revisionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">طلب تعديل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.orders.actions.requestRevision', $order->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        عدد مرات التعديل المتبقية: {{ 3 - $order->revision_count }} من أصل 3
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">أسباب التعديل والملاحظات</label>
                        <textarea name="reason" class="form-control" rows="4" required placeholder="اشرح بالتفصيل ما يجب تعديله..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold rounded-pill px-4">إرسال طلب التعديل</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-danger">طلب إلغاء</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.orders.actions.cancellation', $order->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger small mb-3">
                        سيتم إرسال طلب إلغاء للطرف الآخر ولن يتم إلغاء الطلب فعلياً إلا بعد موافقته.
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">سبب الإلغاء</label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="لماذا ترغب في إلغاء الطلب؟"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">تراجع</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">إرسال طلب الإلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Dispute Modal -->
<div class="modal fade" id="disputeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-danger">رفع نزاع للإدارة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.orders.actions.dispute', $order->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger small mb-3">
                        استخدم هذا الخيار فقط في حال عدم التوصل لاتفاق مع الطرف الآخر. سيتم إيقاف الطلب وتدخل الإدارة للحكم في المشكلة.
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">سبب النزاع والمشكلة</label>
                        <textarea name="reason" class="form-control" rows="5" required placeholder="اشرح المشكلة بالتفصيل للإدارة..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">رفع النزاع</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tracking Modal -->
<div class="modal fade" id="trackingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">إضافة معلومات التتبع (الشحن)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.orders.actions.markShipped', $order->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">شركة الشحن (اختياري)</label>
                        <input type="text" name="carrier" class="form-control" placeholder="مثل: أرامكس، سمسا، DHL...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">رقم التتبع (اختياري)</label>
                        <input type="text" name="tracking_number" class="form-control" placeholder="رقم بوليصة الشحن">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">رابط التتبع (اختياري)</label>
                        <input type="url" name="tracking_url" class="form-control" placeholder="https://...">
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">تأكيد الشحن</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
