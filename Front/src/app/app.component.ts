import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { SeatlookforAppComponent } from './seatlookfor-app/seatlookfor-app.component';

@Component({
  selector: 'app-root',
  imports: [RouterOutlet, SeatlookforAppComponent],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {
  title = 'Front';
}
