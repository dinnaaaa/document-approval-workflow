<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container">

        {{-- Brand --}}
        <a
            class="navbar-brand fw-bold text-primary"
            href="{{ route('dashboard') }}"
        >
            Document Approval
        </a>

        {{-- Mobile Toggle --}}
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            {{-- Navigation --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-semibold' : '' }}"
                        href="{{ route('dashboard') }}"
                    >
                        <i class="bi bi-grid-1x2 me-1"></i>
                        Dashboard
                    </a>
                </li>

                {{-- =====================================================
                     STAFF NAVIGATION
                ====================================================== --}}
                @if (auth()->user()->role?->name === 'Staff')

                    {{-- Dokumen Saya --}}
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('documents.index') || request()->routeIs('documents.show') ? 'active fw-semibold' : '' }}"
                            href="{{ route('documents.index') }}"
                        >
                            <i class="bi bi-file-earmark-text me-1"></i>
                            Dokumen Saya
                        </a>
                    </li>

                    {{-- Buat Dokumen --}}
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('documents.create') ? 'active fw-semibold' : '' }}"
                            href="{{ route('documents.create') }}"
                        >
                            <i class="bi bi-plus-circle me-1"></i>
                            Buat Dokumen
                        </a>
                    </li>

                {{-- =====================================================
                     SUPERVISOR / MANAGER NAVIGATION
                ====================================================== --}}
                @elseif (in_array(auth()->user()->role?->name, ['Supervisor', 'Manager'], true))

                    {{-- Persetujuan --}}
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('approvals.*') ? 'active fw-semibold' : '' }}"
                            href="{{ route('approvals.index') }}"
                        >
                            <i class="bi bi-clipboard-check me-1"></i>
                            Persetujuan
                        </a>
                    </li>

                {{-- =====================================================
                     ADMIN NAVIGATION
                ====================================================== --}}
                @elseif (auth()->user()->role?->name === 'Admin')

                    {{-- Semua Dokumen --}}
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('documents.*') ? 'active fw-semibold' : '' }}"
                            href="{{ route('documents.index') }}"
                        >
                            <i class="bi bi-file-earmark-text me-1"></i>
                            Dokumen
                        </a>
                    </li>

                @endif

                {{-- =====================================================
                     NOTIFICATIONS
                ====================================================== --}}
                <li class="nav-item">
                    <a
                        class="nav-link d-flex align-items-center gap-1 {{ request()->routeIs('notifications.*') ? 'active fw-semibold' : '' }}"
                        href="{{ route('notifications.index') }}"
                    >
                        <i class="bi bi-bell"></i>

                        <span>Notifikasi</span>

                        @php
                            $unreadNotificationCount = auth()->user()
                                ->unreadNotifications()
                                ->count();
                        @endphp

                        @if ($unreadNotificationCount > 0)
                            <span class="badge bg-danger rounded-pill">
                                {{ $unreadNotificationCount }}
                            </span>
                        @endif
                    </a>
                </li>

            </ul>

            {{-- User Menu --}}
            <ul class="navbar-nav">

                <li class="nav-item dropdown">

                    <a
                        class="nav-link dropdown-toggle d-flex align-items-center"
                        href="#"
                        id="userDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <i class="bi bi-person-circle me-2"></i>

                        <span>
                            {{ Auth::user()->name }}
                        </span>
                    </a>

                    <ul
                        class="dropdown-menu dropdown-menu-end shadow-sm"
                        aria-labelledby="userDropdown"
                    >

                        {{-- User Information --}}
                        <li>
                            <div class="dropdown-header">
                                <div class="fw-semibold text-dark">
                                    {{ Auth::user()->name }}
                                </div>

                                <small class="text-muted">
                                    {{ Auth::user()->email }}
                                </small>
                            </div>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        {{-- Profile --}}
                        <li>
                            <a
                                class="dropdown-item"
                                href="{{ route('profile.edit') }}"
                            >
                                <i class="bi bi-person me-2"></i>
                                Profile
                            </a>
                        </li>

                        {{-- Logout --}}
                        <li>
                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="dropdown-item"
                                >
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Log Out
                                </button>
                            </form>
                        </li>

                    </ul>

                </li>

            </ul>

        </div>
    </div>
</nav>