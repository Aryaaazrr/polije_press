 <aside id="sidebar" class="sidebar">

     <ul class="sidebar-nav" id="sidebar-nav">

         <li class="nav-item">
             @if (Auth::user()->id_role == 1)
                 <a class="@if (request()->is('admin/dashboard')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('admin.dashboard') }}">
                     <i class="bi bi-grid"></i>
                     <span>Dashboard</span>
                 </a>
             @elseif (Auth::user()->id_role == 2)
                 <a class="@if (request()->is('dashboard')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('dashboard') }}">
                     <i class="bi bi-grid"></i>
                     <span>Dashboard</span>
                 </a>
             @elseif (Auth::user()->id_role == 3)
                 <a class="@if (request()->is('editor-naskah/dashboard')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('editor.naskah.dashboard') }}">
                     <i class="bi bi-grid"></i>
                     <span>Dashboard</span>
                 </a>
             @elseif (Auth::user()->id_role == 4)
                 <a class="@if (request()->is('editor-akuisisi/dashboard')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('editor.akuisisi.dashboard') }}">
                     <i class="bi bi-grid"></i>
                     <span>Dashboard</span>
                 </a>
             @else
                 <a class="@if (request()->is('pengelola/dashboard')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('pengelola.dashboard') }}">
                     <i class="bi bi-grid"></i>
                     <span>Dashboard</span>
                 </a>
             @endif
         </li>
         <li class="nav-item">
             @if (Auth::user()->id_role == 1)
                 <a class="@if (request()->is('admin/naskah') ||
                         Str::contains(request()->url(), 'admin/naskah/add') ||
                         Str::contains(request()->url(), 'admin/naskah/edit/')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('admin.naskah') }}">
                     <i class="bi bi-book"></i>
                     <span>Naskah</span>
                 </a>
             @elseif (Auth::user()->id_role == 2)
                 <a class="@if (request()->is('naskah') ||
                         Str::contains(request()->url(), 'admin/naskah/add') ||
                         Str::contains(request()->url(), 'admin/naskah/edit/')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('naskah') }}">
                     <i class="bi bi-book"></i>
                     <span>Naskah</span>
                 </a>
             @elseif (Auth::user()->id_role == 3)
                 <a class="@if (request()->is('editor-naskah/tugas') ||
                         Str::contains(request()->url(), 'admin/naskah/add') ||
                         Str::contains(request()->url(), 'admin/naskah/edit/')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('editor.naskah.tugas') }}">
                     <i class="bi bi-clipboard-check"></i>
                     <span>Tugas</span>
                 </a>
             @elseif (Auth::user()->id_role == 4)
                 <a class="@if (request()->is('editor-akuisisi/tugas') ||
                         Str::contains(request()->url(), 'admin/naskah/add') ||
                         Str::contains(request()->url(), 'admin/naskah/edit/')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('editor.akuisisi.tugas') }}">
                     <i class="bi bi-clipboard-check"></i>
                     <span>Tugas</span>
                 </a>
             @else
                 <a class="@if (request()->is('pengelola/naskah') ||
                         Str::contains(request()->url(), 'admin/naskah/add') ||
                         Str::contains(request()->url(), 'admin/naskah/edit/')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('pengelola.naskah') }}">
                     <i class="bi bi-book"></i>
                     <span>Naskah</span>
                 </a>
             @endif
         </li>

         @if (Auth::user()->id_role == 1)
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
             <li class="nav-item">
                 <a class="@if (request()->routeIs('admin.pengguna') ||
                         Str::contains(request()->url(), 'admin/pengguna/add') ||
                         Str::contains(request()->url(), 'admin/pengguna/edit')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('admin.pengguna') }}">
                     <i class="bi bi-people"></i>
                     <span>Pengguna</span>
                 </a>
             </li>
         @elseif (Auth::user()->id_role == 5)
             <li class="nav-item">
                 <a class="@if (request()->is('pengelola/editor')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('pengelola.editor') }}">
                     <i class="bi bi-clipboard-check"></i>
                     <span>Tugas Editor</span>
                 </a>
             </li>
         @endif



         <li class="nav-heading">Pengaturan</li>

         <li class="nav-item">
             @if (Auth::user()->id_role == 1)
                 <a class="@if (request()->is('admin/profile')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('admin.profile') }}">
                     <i class="bi bi-person"></i>
                     <span>Profil</span>
                 </a>
             @elseif (Auth::user()->id_role == 2)
                 <a class="@if (request()->is('profile')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('profile') }}">
                     <i class="bi bi-person"></i>
                     <span>Profil</span>
                 </a>
             @elseif (Auth::user()->id_role == 3)
                 <a class="@if (request()->is('editor-naskah/profile')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('editor.naskah.profile') }}">
                     <i class="bi bi-person"></i>
                     <span>Profil</span>
                 </a>
             @elseif (Auth::user()->id_role == 4)
                 <a class="@if (request()->is('editor-akuisisi/profile')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('editor.akuisisi.profile') }}">
                     <i class="bi bi-person"></i>
                     <span>Profil</span>
                 </a>
             @else
                 <a class="@if (request()->is('pengelola/profile')) nav-link @else nav-link collapsed @endif"
                     href="{{ route('pengelola.profile') }}">
                     <i class="bi bi-person"></i>
                     <span>Profil</span>
                 </a>
             @endif
         </li>

     </ul>

 </aside>
