@extends('adminlte::page')

@section('title', 'Member Profile: ' . $member->full_name)

@section('content_header')
    <h1 class="m-0">Member Profile</h1>
@stop

@section('content')
    <style>
        /* Print styles */
        @media print {
            body * {
                visibility: hidden;
            }
            #printArea, #printArea * {
                visibility: visible;
            }
            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
            .profile-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge-modern {
                border: 1px solid #000;
                color: #000 !important;
                background: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .info-card, .section-card {
                break-inside: avoid;
                border: 1px solid #cbd5e1 !important;
                box-shadow: none !important;
            }
        }

        /* Modern styling */
        .profile-wrapper {
            max-width: 1400px;
            margin: 0 auto;
        }

        .profile-header {
            background: linear-gradient(135deg, #1e293b 0%, #3b82f6 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 2rem 2.5rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .profile-avatar {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .profile-title h2 {
            font-size: 2.2rem;
            margin-bottom: 0.25rem;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .profile-title p {
            margin-bottom: 0.75rem;
            opacity: 0.9;
            font-size: 1rem;
        }

        .profile-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        .badge-modern {
            background: rgba(255,255,255,0.15);
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            font-size: 0.9rem;
            font-weight: 500;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        /* Two-column layout */
        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 1.8rem;
            padding: 2rem 2.5rem;
            background: #f8fafc;
        }

        @media (max-width: 992px) {
            .profile-grid {
                grid-template-columns: 1fr;
                padding: 1.5rem;
            }
            .profile-header {
                padding: 1.5rem;
            }
        }

        /* Sidebar cards */
        .info-card {
            background: white;
            border-radius: 18px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            padding: 1.8rem 1.5rem;
            margin-bottom: 1.8rem;
            border: 1px solid #eef2f6;
            transition: box-shadow 0.2s;
        }

        .info-card:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }

        .info-card:last-child {
            margin-bottom: 0;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 0.9rem;
        }

        .card-title i {
            color: #3b82f6;
            font-size: 1.3rem;
            width: 1.5rem;
        }

        .info-row {
            display: flex;
            margin-bottom: 1rem;
            word-break: break-word;
        }

        .info-label {
            width: 125px;
            flex-shrink: 0;
            font-size: 0.8rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            flex: 1;
            color: #1e293b;
            font-weight: 500;
        }

        /* Main content cards */
        .section-card {
            background: white;
            border-radius: 18px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            padding: 1.8rem 1.8rem;
            margin-bottom: 1.8rem;
            border: 1px solid #eef2f6;
        }

        .entry-item {
            margin-bottom: 1.5rem;
        }

        .entry-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
            margin-bottom: 0.25rem;
        }

        .entry-title {
            font-weight: 700;
            color: #0f172a;
            font-size: 1.05rem;
        }

        .entry-subtitle {
            color: #475569;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .entry-period {
            background: #eef2ff;
            padding: 0.2rem 0.8rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #3b82f6;
        }

        hr {
            border: 0;
            border-top: 1px solid #e2e8f0;
            margin: 1.2rem 0;
        }

        .certificate-link {
            margin-top: 0.6rem;
        }

        .btn-outline-primary {
            border: 1px solid #3b82f6;
            color: #3b82f6;
            padding: 0.3rem 1.2rem;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-block;
            text-decoration: none;
        }

        .btn-outline-primary:hover {
            background: #3b82f6;
            color: white;
        }

        /* Action buttons in header */
        .action-buttons {
            display: flex;
            gap: 0.6rem;
            margin-left: auto;
        }

        .action-buttons .btn {
            border-radius: 30px;
            padding: 0.5rem 1.2rem;
            font-weight: 600;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            .info-row {
                flex-direction: column;
                gap: 0.25rem;
            }
            .info-label {
                width: auto;
            }
        }
    </style>

    {{-- Action Bar (Print & Back) --}}
    <div class="d-flex justify-content-between align-items-center no-print mb-3">
        <div>
            <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Members
            </a>
        </div>
        <div class="d-flex gap-2">
            @can('edit members')
                <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-warning">
                    <i class="fas fa-edit mr-1"></i> Edit Member
                </a>
            @endcan
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print mr-1"></i> Print Profile
            </button>
        </div>
    </div>

    <div class="profile-wrapper" id="printArea">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            {{-- Header --}}
            <div class="profile-header">
                <img src="{{ $member->photo_url ?? asset('images/default.png') }}" alt="Photo" class="profile-avatar">
                <div class="profile-title">
                    <h2>{{ $member->full_name }}</h2>
                    <p><i class="fas fa-id-card mr-2"></i> Membership No: <strong>{{ $member->membership_number }}</strong></p>
                    <div class="profile-badges">
                        <span class="badge-modern">
                            <i class="fas fa-circle mr-1 text-{{ $member->status == 'Active' ? 'success' : 'danger' }}"></i>
                            {{ $member->status }}
                        </span>
                        @if($member->membershipType)
                            <span class="badge-modern">
                                <i class="fas fa-tag mr-1"></i> {{ $member->membershipType->name }}
                            </span>
                        @endif
                    </div>
                </div>
                {{-- Optional: Quick actions inside header --}}
                <div class="action-buttons no-print">
                    @can('delete members')
                        <form action="{{ route('admin.members.destroy', $member) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this member? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

            {{-- Grid Layout --}}
            <div class="profile-grid">
                {{-- Left Column --}}
                <div class="left-column">
                    {{-- Contact --}}
                    <div class="info-card">
                        <div class="card-title">
                            <i class="fas fa-address-card"></i> Contact Information
                        </div>
                        <div class="info-row">
                            <span class="info-label">Phone</span>
                            <span class="info-value">{{ $member->phone }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $member->email ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Current Address</span>
                            <span class="info-value">
                                @if($member->current_village || $member->current_district || $member->current_region || $member->current_country)
                                    {{ implode(', ', array_filter([$member->current_village, $member->current_district, $member->current_region, $member->current_country])) }}
                                @else
                                    —
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Permanent Address</span>
                            <span class="info-value">{{ $member->address ?? '—' }}</span>
                        </div>
                    </div>

                    {{-- Personal Details --}}
                    <div class="info-card">
                        <div class="card-title">
                            <i class="fas fa-user"></i> Personal Details
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date of Birth</span>
                            <span class="info-value">{{ $member->date_of_birth ? $member->date_of_birth->format('d M Y') : '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Place of Birth</span>
                            <span class="info-value">{{ $member->place_of_birth ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">National ID</span>
                            <span class="info-value">{{ $member->national_id ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Nationality</span>
                            <span class="info-value">{{ $member->nationality ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Religion</span>
                            <span class="info-value">{{ $member->religion ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tribe</span>
                            <span class="info-value">{{ $member->tribe ?? '—' }}</span>
                        </div>
                    </div>

                    {{-- Family --}}
                    <div class="info-card">
                        <div class="card-title">
                            <i class="fas fa-users"></i> Family
                        </div>
                        <div class="info-row">
                            <span class="info-label">Marital Status</span>
                            <span class="info-value">{{ $member->maritalStatus->name ?? '—' }}</span>
                        </div>
                        @if($member->spouse_first_name || $member->spouse_surname)
                        <div class="info-row">
                            <span class="info-label">Spouse</span>
                            <span class="info-value">
                                {{ trim($member->spouse_first_name . ' ' . $member->spouse_middle_name . ' ' . $member->spouse_surname) }}
                                @if($member->spouse_phone) <br><small class="text-muted">{{ $member->spouse_phone }}</small> @endif
                            </span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">Children</span>
                            <span class="info-value">{{ $member->children_count ?? 0 }}</span>
                        </div>
                    </div>

                    {{-- Employment --}}
                    <div class="info-card">
                        <div class="card-title">
                            <i class="fas fa-briefcase"></i> Employment
                        </div>
                        @if($member->is_employed)
                            <div class="info-row">
                                <span class="info-label">Employer</span>
                                <span class="info-value">{{ $member->employer_name }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Job Title</span>
                                <span class="info-value">{{ $member->job_title }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Status</span>
                                <span class="info-value">{{ ucfirst($member->employment_status) }}</span>
                            </div>
                            @if($member->employer_address)
                            <div class="info-row">
                                <span class="info-label">Work Address</span>
                                <span class="info-value">{{ $member->employer_address }}</span>
                            </div>
                            @endif
                        @else
                            <p class="text-muted mb-0">Not currently employed</p>
                        @endif
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="right-column">
                    {{-- Academic History --}}
                    <div class="section-card">
                        <div class="card-title">
                            <i class="fas fa-graduation-cap"></i> Academic History
                        </div>
                        @php $academics = collect($member->academic_history ?? []); @endphp
                        @if($academics->isEmpty())
                            <p class="text-muted mb-0">No academic records.</p>
                        @else
                            @foreach($academics as $academic)
                                <div class="entry-item">
                                    <div class="entry-header">
                                        <span class="entry-title">{{ $academic['institution_name'] ?? 'Institution' }}</span>
                                        <span class="entry-period">{{ $academic['start_year'] ?? '' }} – {{ $academic['end_year'] ?? '' }}</span>
                                    </div>
                                    <div class="entry-subtitle">{{ $academic['education_level'] ?? 'Qualification' }}</div>
                                    @if(!empty($academic['certificate_file']) && is_string($academic['certificate_file']))
                                        <div class="certificate-link no-print">
                                            <a href="{{ asset('storage/certificates/'.$academic['certificate_file']) }}" target="_blank" class="btn-outline-primary">
                                                <i class="fas fa-file-pdf mr-1"></i> View Certificate
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                @if(!$loop->last)<hr>@endif
                            @endforeach
                        @endif
                    </div>

                    {{-- Professional Experience --}}
                    <div class="section-card">
                        <div class="card-title">
                            <i class="fas fa-briefcase"></i> Professional Experience
                        </div>
                        @php $professionals = collect($member->professional_history ?? []); @endphp
                        @if($professionals->isEmpty())
                            <p class="text-muted mb-0">No professional experience recorded.</p>
                        @else
                            @foreach($professionals as $exp)
                                <div class="entry-item">
                                    <div class="entry-header">
                                        <span class="entry-title">{{ $exp['occupation'] ?? 'Position' }}</span>
                                        @if(!empty($exp['years_of_experience']))
                                            <span class="entry-period">{{ $exp['years_of_experience'] }} yrs</span>
                                        @endif
                                    </div>
                                    <div class="entry-subtitle">{{ $exp['employer'] ?? 'Employer' }}</div>
                                    @if(!empty($exp['work_address']))
                                        <small class="text-muted d-block mt-1"><i class="fas fa-map-marker-alt mr-1"></i> {{ $exp['work_address'] }}</small>
                                    @endif
                                </div>
                                @if(!$loop->last)<hr>@endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection