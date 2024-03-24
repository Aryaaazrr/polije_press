 <aside id="sidebar" class="sidebar">

     <ul class="sidebar-nav" id="sidebar-nav">

         <li class="nav-item">
             <a class="@if (request()->is('admin/dashboard')) nav-link @else nav-link collapsed @endif"
                 href="{{ route('admin.dashboard') }}">
                 <i class="bi bi-grid"></i>
                 <span>Dashboard</span>
             </a>
         </li>
         <li class="nav-item">
             <a class="@if (request()->is('admin/naskah') ||
                     Str::contains(request()->url(), 'admin/naskah/add') ||
                     Str::contains(request()->url(), 'admin/naskah/edit/')) nav-link @else nav-link collapsed @endif"
                 href="{{ route('admin.naskah') }}">
                 <i class="bi bi-book"></i>
                 <span>Naskah</span>
             </a>
         </li>
         <li class="nav-item">
             <a class="@if (request()->is('admin/kategori')) nav-link @else nav-link collapsed @endif"
                 href="{{ route('admin.kategori') }}">
                 <i class="bi bi-collection"></i>
                 <span>Kategori</span>
             </a>
         </li>
         <li class="nav-item">
             <a class="@if (request()->is('admin/editor')) nav-link @else nav-link collapsed @endif"
                 href="{{ route('admin.editor') }}">
                 <i class="bi bi-clipboard-check"></i>
                 <span>Tugas Editor</span>
             </a>
         </li>

         {{-- <li class="nav-item">
             <a class="@if (request()->is('admin/naskah') || Str::contains(request()->url(), 'admin/kategori') || Str::contains(request()->url(), 'admin/naskah/add')) nav-link @else nav-link collapsed @endif"
                 data-bs-target="#naskah" data-bs-toggle="collapse" href="">
                 <i class="bi bi-menu-button-wide"></i><span>Naskah</span><i class="bi bi-chevron-down ms-auto"></i>
             </a>
             <ul id="naskah" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                 <li>
                     <a href="{{ route('admin.naskah') }}">
                         <i class="bi bi-circle"></i><span>Monitoring Naskah</span>
                     </a>
                 </li>
             </ul>
             <ul id="naskah" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                 <li>
                     <a href="{{ route('admin.kategori') }}">
                         <i class="bi bi-circle"></i><span>Kategori</span>
                     </a>
                 </li>
             </ul>
         </li> --}}

         <li class="nav-item">
             <a class="@if (request()->routeIs('admin.pengguna') ||
                     Str::contains(request()->url(), 'admin/pengguna/add') ||
                     Str::contains(request()->url(), 'admin/pengguna/edit')) nav-link @else nav-link collapsed @endif"
                 href="{{ route('admin.pengguna') }}">
                 <i class="bi bi-people"></i>
                 <span>Pengguna</span>
             </a>
         </li>


         <li class="nav-heading">Pengaturan</li>

         <li class="nav-item">
             <a class="@if (request()->is('admin/profile')) nav-link @else nav-link collapsed @endif"
                 href="{{ route('admin.profile') }}">
                 <i class="bi bi-person"></i>
                 <span>Profil</span>
             </a>
         </li>

     </ul>

 </aside>
