import { Component, HostListener } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterModule } from '@angular/router';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './navbar.component.html',
  styleUrl: './navbar.component.css'
})
export class NavbarComponent {
  isUserMenuOpen = false;

  constructor(
    private auth: AuthService,
    private router: Router
  ) {}

  toggleUserMenu() {
    this.isUserMenuOpen = !this.isUserMenuOpen;
  }

  @HostListener('document:click', ['$event'])
  onDocumentClick(event: MouseEvent) {
    const userIcon = document.querySelector('.user-icon');
    if (userIcon && !userIcon.contains(event.target as Node)) {
      this.isUserMenuOpen = false;
    }
  }

  logout() {
    this.auth.logout();
    this.isUserMenuOpen = false;
  }

  navigateToUserOrRegister() {
    if (this.auth.isLoggedIn()) {
      this.router.navigate(['/usuario']);
    } else {
      this.router.navigate(['/registro']);
    }
  }
}
