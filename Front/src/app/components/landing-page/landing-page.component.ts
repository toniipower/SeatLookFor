import { Component, OnInit, AfterViewInit } from '@angular/core';
import { NavbarComponent } from '../navbar/navbar.component';
import { FooterComponent } from '../footer/footer.component';
import { CardService } from '../../services/card.service';
import { CommonModule } from '@angular/common';
import { Evento } from '../../models/evento.model';
import { RouterLink } from '@angular/router';
import { AcortarDescripcionPipe } from './acortar-descripcion.pipe';
import { animate } from 'motion';




@Component({
  selector: 'app-landing-page',
  imports: [NavbarComponent, FooterComponent, CommonModule, RouterLink, AcortarDescripcionPipe],
  templateUrl: './landing-page.component.html',
  styleUrl: './landing-page.component.css'
})
export class LandingPageComponent implements OnInit, AfterViewInit {
  cards: Evento[] = [];

  ngAfterViewInit() {
    const spans = document.querySelectorAll('h2 span');

    animate(spans, {
      y: [
        { to: '-2.75rem', easing: 'ease-out', duration: 0.6 },
        { to: '0', easing: 'ease-out', duration: 0.8, delay: 0.1 }
      ],
      rotate: {
        from: '-1turn',
        delay: 0
      },
      delay: (_: any, i: any) => i * 0.05,
      easing: 'ease-in-out',
      loop: true,
      loopDelay: 1
    } as any);
  }



  constructor(private cardService: CardService) { }

  ngOnInit() {
    this.fetchCards();
  }

  seClico() {
    console.log("se ha clicado el boton para ir a reservas");

  }

fetchCards() {
    const baseUrl = 'http://localhost'; // O usa environment.backendUrl

    this.cardService.getCards().subscribe(
      (data) => {
        this.cards = data.map(evento => ({
                 
          ...evento,
          portada: `${baseUrl}/${evento.portada}`
        }));

        console.log('Cards con URL de imagen:', this.cards);
      },
      (error) => {
        console.error('Error al obtener los eventos:', error);
      }
    );
  }

}
