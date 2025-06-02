import { Component, Input, Output, EventEmitter } from '@angular/core';
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
  @Output() asientosSeleccionadosChange = new EventEmitter<Asiento[]>();

  asientosSeleccionados: Asiento[] = [];

  selecionarAsiento(asiento: Asiento) {
    if (asiento.estado === 'ocupado') return;
    
    const index = this.asientosSeleccionados.findIndex(a => 
      a.ejeX === asiento.ejeX && a.ejeY === asiento.ejeY
    );

    if (index === -1) {
      this.asientosSeleccionados.push(asiento);
    } else {
      this.asientosSeleccionados.splice(index, 1);
    }

    this.asientosSeleccionadosChange.emit(this.asientosSeleccionados);
  }

  estaSeleccionado(asiento: Asiento): boolean {
    return this.asientosSeleccionados.some(a => 
      a.ejeX === asiento.ejeX && a.ejeY === asiento.ejeY
    );
  }
}
