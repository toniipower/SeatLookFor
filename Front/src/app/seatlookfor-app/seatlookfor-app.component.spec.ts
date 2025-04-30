import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SeatlookforAppComponent } from './seatlookfor-app.component';

describe('SeatlookforAppComponent', () => {
  let component: SeatlookforAppComponent;
  let fixture: ComponentFixture<SeatlookforAppComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [SeatlookforAppComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SeatlookforAppComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
