import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { EventoService } from '../../../services/evento.service';
import { Evento } from '../../../models/evento.model';
import { EventosCardComponent } from '../../eventos-card/eventos-card.component';
import { FooterComponent } from '../../footer/footer.component';


@Component({
  selector: 'app-todos-eventos',
  imports: [CommonModule, FooterComponent, EventosCardComponent],
  templateUrl: './todos-eventos.component.html',
  styleUrl: './todos-eventos.component.css'
})
export class TodosEventosComponent {

}
