import { Component } from '@angular/core';
import { LandingPageComponent } from './components/landing-page/landing-page.component';
import { FooterComponent } from './components/footer/footer.component';

@Component({
  selector: 'app-seatlookfor-app',
  imports: [LandingPageComponent, FooterComponent],
  templateUrl: './seatlookfor-app.component.html',
  styleUrl: './seatlookfor-app.component.css'
})
export class SeatlookforAppComponent {

}
