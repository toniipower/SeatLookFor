import { Component, OnInit, AfterViewInit, OnDestroy } from '@angular/core';
import { NavbarComponent } from '../navbar/navbar.component';
import { FooterComponent } from '../footer/footer.component';
import { CardService } from '../../services/card.service';
import { CommonModule } from '@angular/common';
import { Evento } from '../../models/evento.model';
import { RouterLink } from '@angular/router';
import { AcortarDescripcionPipe } from './acortar-descripcion.pipe';
import { environment } from '../../../environments/environment';

@Component({
  selector: 'app-landing-page',
  standalone: true,
  imports: [NavbarComponent, FooterComponent, CommonModule, RouterLink, AcortarDescripcionPipe],
  templateUrl: './landing-page.component.html',
  styleUrl: './landing-page.component.css'
})
export class LandingPageComponent implements OnInit {
  animatedText = "Nuestras Recomendaciones"
  cards: Evento[] = [];
  letters: string[] = [];
  baseUrl = environment.apiUrl.replace('/api', '');

  constructor(private cardService: CardService) { }

  /* ngOnInit() {
    this.letters = this.animatedText.split('');
  } */

  ngOnInit() {
    this.fetchCards();
  }

  seClico() {
    console.log("se ha clicado el boton para ir a reservas");

  }

  fetchCards() {
    this.cardService.getCards().subscribe(
      (data) => {
        this.cards = data.map(evento => ({
          ...evento,
          portada: `${this.baseUrl}/${evento.portada}`
        }));

        console.log('Cards con URL de imagen:', this.cards);
      },
      (error) => {
        console.error('Error al obtener los eventos:', error);
      }
    );
  }
}
