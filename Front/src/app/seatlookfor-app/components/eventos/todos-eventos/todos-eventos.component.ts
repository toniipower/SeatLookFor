import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { EventoService } from '../../../services/evento.service';
import { Evento } from '../../../models/evento.model';

@Component({
  selector: 'app-todos-eventos',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './todos-eventos.component.html',
  styleUrl: './todos-eventos.component.css'
})
export class TodosEventosComponent implements OnInit {
  eventos: Evento[] = [];
  loading: boolean = true;
  error: string | null = null;

    cards: Evento[] = [];
  
    constructor(private evento: EventoService) {}
  
    ngOnInit() {
      this.fetchCards();
    }
  
    fetchCards() {
      this.evento.getEventos().subscribe(
        (data) => {
          this.cards = data;
          console.log(data);
          
          console.log('Cards recibidas:', this.cards);
        },
        (error) => {
          console.error('Error fetching cards:', error);
        }
      );
    }
}
