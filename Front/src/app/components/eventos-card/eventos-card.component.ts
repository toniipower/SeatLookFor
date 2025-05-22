import { Component, Input, OnChanges, OnInit } from '@angular/core';
import { Evento } from '../../models/evento.model';
import { EventoService } from '../../services/evento.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-eventos-card',
  imports: [CommonModule],
  templateUrl: './eventos-card.component.html',
  styleUrl: './eventos-card.component.css'
})
export class EventosCardComponent implements OnChanges {
  @Input() eventos: Evento[] = [];
  cards: Evento[] = [];
  loading: boolean = true;
  error: string | null = null;



  pageSize = 4;
  currentPage = 1;
  totalItems = 0;
  totalPages = 0;

  constructor(private eventoService: EventoService) { }

/*   ngOnInit() {
    if (this.eventos && this.eventos.length > 0) {
      this.totalItems = this.eventos.length;
      this.totalPages = Math.ceil(this.totalItems / this.pageSize);
      this.updatePagedCards();
    }
  } */

  ngOnChanges() {
    if (this.eventos && this.eventos.length > 0) {
      this.totalItems = this.eventos.length;
      this.totalPages = Math.ceil(this.totalItems / this.pageSize);
      this.currentPage = 1;
      this.updatePagedCards();
    } else {
      this.cards = [];
      this.totalItems = 0;
      this.totalPages = 0;
    }

    this.loading = false;
  }


  updatePagedCards() {
    const startIndex = (this.currentPage - 1) * this.pageSize;
    const endIndex = Math.min(startIndex + this.pageSize, this.totalItems);
    this.cards = this.eventos.slice(startIndex, endIndex);
  }


  // Paginación desde el frontend
  fetchCards() {
    this.loading = true;
    this.eventoService.getEventos().subscribe(
      (data) => {
        this.eventos = data; // Guarda todos los eventos
        this.totalItems = this.eventos.length;
        this.totalPages = Math.ceil(this.totalItems / this.pageSize);
        this.updatePagedCards(); // Llama a la función para paginar
        this.loading = false;
        console.log('Eventos recibidos:', this.eventos);
      },
      (error) => {
        this.error = 'Error eventos.';
        this.loading = false;
        console.error('Error eventos:', error);
      }
    );
  }

  nextPage() {
    if (this.currentPage < this.totalPages) {
      this.currentPage++;
      this.updatePagedCards();
    }
  }

  previousPage() {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.updatePagedCards();
    }
  }

  goToPage(page: number) {
    if (page >= 1 && page <= this.totalPages && page !== this.currentPage) {
      this.currentPage = page;
      this.updatePagedCards();
    }
  }

  getPages(): number[] {
    const pageCount = Math.ceil(this.totalItems / this.pageSize);
    return Array.from({ length: pageCount }, (_, i) => i + 1);
  }
}