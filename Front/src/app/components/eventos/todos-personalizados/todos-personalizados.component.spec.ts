import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TodosPersonalizadosComponent } from './todos-personalizados.component';

describe('TodosPersonalizadosComponent', () => {
  let component: TodosPersonalizadosComponent;
  let fixture: ComponentFixture<TodosPersonalizadosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [TodosPersonalizadosComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(TodosPersonalizadosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
