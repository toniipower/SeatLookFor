import { Component, OnInit } from '@angular/core';
import { NavbarComponent } from '../navbar/navbar.component';
import { FooterComponent } from '../footer/footer.component';
import { CardService } from '../../services/card.service';
import { CommonModule } from '@angular/common';
import { Evento } from '../../models/evento.model';

@Component({
  selector: 'app-landing-page',
  imports: [NavbarComponent, FooterComponent, CommonModule],
  templateUrl: './landing-page.component.html',
  styleUrl: './landing-page.component.css'
})
export class LandingPageComponent implements OnInit {
  cards: Evento[] = [];

  constructor(private cardService: CardService) {}

  ngOnInit() {
    this.fetchCards();
  }

  fetchCards() {
    this.cardService.getCards().subscribe(
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
