import { Component, Input } from '@angular/core';
import { Asiento } from '../../models/asiento.model';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-asientos',
  imports: [CommonModule],
  templateUrl: './asientos.component.html',
  styleUrl: './asientos.component.css'
})
export class AsientosComponent {
  @Input() asientos: Asiento[] = [];

  
}
